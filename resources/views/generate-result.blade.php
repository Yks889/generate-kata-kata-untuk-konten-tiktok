@extends('layouts.app')

@section('content')
<h3>Hasil Generate Konten</h3>

<div class="card mb-4">
  <div class="card-body">
    <h5><strong>Judul:</strong> {{ $judul }}</h5>
    <p><strong>Tema:</strong> {{ $tema }}</p>
    <p><strong>Gaya:</strong> {{ $gaya }}</p>
    <hr>
    <p><strong>Konten:</strong> {{ $konten }}</p>
    <p><strong>Deskripsi:</strong> {{ $deskripsi }}</p>
    <p><strong>Hashtag:</strong> {{ $hashtag }}</p>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const waNumber = '6281332908050'; // Format internasional tanpa tanda +
    const text = `
✅ Konten (Isi Satu Kalimat):
"{{ $konten }}"

🖊️ Deskripsi:
{{ $deskripsi }}

🔖 Hashtag:
{{ $hashtag }}
    `.trim();

    const encodedText = encodeURIComponent(text);
    const waUrl = `https://wa.me/${waNumber}?text=${encodedText}`;

    // Redirect otomatis ke WhatsApp dengan pesan yang sudah terisi
    window.location.href = waUrl;
});
</script>
@endsection
