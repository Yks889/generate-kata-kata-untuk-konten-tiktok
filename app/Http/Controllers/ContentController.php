<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneratedContent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContentController extends Controller
{
    public function form()
    {
        return view('generate-form');
    }

    public function handleGenerate(Request $request)
    {
        Log::info('🚀 handleGenerate called', $request->all());

        $request->validate([
            'tema' => 'required|string',
            'gaya' => 'required|string',
        ]);

        Log::info('🔍 Mode: ', ['mode' => $request->input('mode')]);

        return $request->input('mode') === 'gpt'
            ? $this->generateFromGemini($request)
            : $this->generateResult($request);
    }

    public function generateResult(Request $request)
    {
        $tema = strtolower($request->input('tema'));
        $gaya = strtolower($request->input('gaya'));

        $templatePath = resource_path('data/templates.json');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'File template tidak ditemukan.');
        }

        $template = json_decode(file_get_contents($templatePath), true);

        $konten = $template[$tema][$gaya] ?? "Konten untuk tema '$tema' belum tersedia.";
        $judul = fake()->randomElement($template['judul'] ?? ['Judul tidak ditemukan']);
        $deskripsi = fake()->randomElement($template['deskripsi'] ?? ['Deskripsi tidak ditemukan']);
        $hashtag = is_array($template['hashtag'] ?? []) ? implode(' ', $template['hashtag']) : 'Hashtag tidak ditemukan';

        // Simpan ke database
        GeneratedContent::create(compact('tema', 'gaya', 'judul', 'konten', 'deskripsi', 'hashtag'));

        // Kirim ke Telegram
        $this->sendToTelegram($konten, $deskripsi, $hashtag);

        return view('generate-result', compact('konten', 'judul', 'deskripsi', 'hashtag', 'tema', 'gaya'));
    }

    public function generateFromGemini(Request $request)
    {
        $tema = strtolower($request->input('tema'));
        $gaya = strtolower($request->input('gaya'));

        $prompt = "Buat konten TikTok Islami singkat dengan format:\n"
                . "**Konten:** (Isi satu kalimat pendek saja yang relatable dan berisi semangat atau sindiran positif)\n"
                . "**Deskripsi:** (Berisi penjelasan singkat dan penyemangat, maksimal 4 baris)\n"
                . "**Hashtag:** (Daftar hashtag saja tanpa tambahan kata)\n\n"
                . "Tema: $tema\nGaya: $gaya";

        Log::info('📡 Request ke Gemini API', ['prompt' => $prompt]);

        $output = $this->callGeminiAPI($prompt, 'gemini-2.5-pro');

        if (!$output) {
            Log::warning('⚠️ Fallback ke gemini-2.5-flash');
            $output = $this->callGeminiAPI($prompt, 'gemini-2.5-flash');
        }

        if (!$output) {
            return back()->with('error', 'Gagal mendapatkan konten dari Gemini API.');
        }

        $judul = 'AI Generated';
        $konten = $this->extractBetween($output, '**Konten:**', '**Deskripsi:**') ?? 'Konten tidak ditemukan';
        $deskripsi = $this->extractBetween($output, '**Deskripsi:**', '**Hashtag:**') ?? '-';
        $hashtag = $this->extractBetween($output, '**Hashtag:**', '') ?? '-';

        // Simpan ke database
        GeneratedContent::create([
            'tema' => $tema,
            'gaya' => $gaya,
            'judul' => $judul,
            'konten' => trim($konten),
            'deskripsi' => trim($deskripsi),
            'hashtag' => trim($hashtag),
        ]);

        // Kirim ke Telegram
        $this->sendToTelegram($konten, $deskripsi, $hashtag);

        return view('generate-result', [
            'konten' => trim($konten),
            'judul' => $judul,
            'deskripsi' => trim($deskripsi),
            'hashtag' => trim($hashtag),
            'tema' => $tema,
            'gaya' => $gaya,
        ]);
    }

    private function callGeminiAPI(string $prompt, string $model)
    {
        $apiKey = env('GEMINI_API_KEY');

        $url = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}";

        Log::info('📡 Gemini Request URL', ['url' => $url]);
        Log::info('📨 Prompt', ['prompt' => $prompt]);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->failed()) {
                Log::error('❌ Gemini API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            $output = $response->json('candidates.0.content.parts.0.text');

            Log::info('✅ Gemini Response Received', ['output' => $output]);

            return $output ?? null;
        } catch (\Exception $e) {
            Log::error('❌ Gemini API Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    private function extractBetween($text, $start, $end = null)
    {
        $pattern = $end
            ? '/' . preg_quote($start, '/') . '(.*?)' . preg_quote($end, '/') . '/s'
            : '/' . preg_quote($start, '/') . '(.*)/s';

        if (preg_match($pattern, $text, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function sendToTelegram(string $konten, string $deskripsi, string $hashtag)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        $message = "✅ <b>Konten (Isi Satu Kalimat)</b>\n\"{$konten}\"\n\n🖊️ <b>Deskripsi</b>\n{$deskripsi}\n\n🔖 <b>Hashtag</b>\n{$hashtag}";

        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]);

            if ($response->failed()) {
                Log::error('❌ Telegram Error', ['response' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::error('❌ Telegram Exception', ['message' => $e->getMessage()]);
        }
    }
}
