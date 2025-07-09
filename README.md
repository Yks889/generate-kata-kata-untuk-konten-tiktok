<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## 🎥 Generator Konten TikTok Islami (Laravel + Gemini AI)

Aplikasi Laravel ini dibuat untuk membantu para kreator dakwah dalam menghasilkan **konten TikTok Islami** yang singkat, inspiratif, dan cocok untuk anak muda.

Proyek ini memungkinkan pengguna untuk:

* Mengisi `tema` dan `gaya` konten.
* Menghasilkan **judul, satu kalimat konten utama, deskripsi singkat, dan hashtag** viral Islami.
* Menggunakan AI (Google Gemini API) untuk menyusun teks konten.
* **Menyimpan hasil ke database** untuk dokumentasi/riwayat.
* **Kirim otomatis** ke:

  * Telegram Bot
  * WhatsApp (pribadi, bukan grup)
* **Redirect otomatis** ke WhatsApp dengan konten siap kirim.

---

## ✨ Fitur Utama

* 🔥 **AI Integration (Gemini)**
  Menggunakan model `gemini-2.5-pro` & fallback `gemini-2.5-flash` untuk hasil terbaik.

* 📄 **Form Generator**
  Input `tema` dan `gaya`, klik generate, lalu langsung tampil hasil.

* 🧠 **Konten Format AI**

  ```
  ✅ Konten (Isi Satu Kalimat):
  "..."

  💊 Deskripsi:
  ...

  🔖 Hashtag:
  ...
  ```

* 📤 **Kirim ke Telegram**
  Menggunakan Bot API Telegram untuk otomatis broadcast konten yang dihasilkan.

* 📲 **Kirim ke WhatsApp**
  Redirect ke WhatsApp pribadi dengan isi pesan yang sudah siap copy-paste.

---

## 🚀 Cara Jalankan

1. Clone project:

   ```bash
   git clone https://github.com/kamu/generator-tiktok-islami.git
   cd generator-tiktok-islami
   ```

2. Install dependency:

   ```bash
   composer install
   ```

3. Salin file `.env` dan atur konfigurasi:

   ```bash
   cp .env.example .env
   ```

   **Tambahkan baris berikut di `.env`:**

   ```
   GEMINI_API_KEY=YOUR_GEMINI_API_KEY
   TELEGRAM_BOT_TOKEN=YOUR_TELEGRAM_BOT_TOKEN
   TELEGRAM_CHAT_ID=YOUR_TELEGRAM_CHAT_ID
   ```

4. Generate key dan migrasi database:

   ```bash
   php artisan key:generate
   php artisan migrate
   ```

5. Jalankan server:

   ```bash
   php artisan serve
   ```

---

## 📅 Struktur Output

* Hasil generate bisa dilihat di `generate-result.blade.php`
* Teks otomatis dikirim ke Telegram
* Redirect otomatis ke WhatsApp dengan isi pesan

---

## 🛡️ Catatan Keamanan

* Token Gemini dan Telegram disimpan aman di `.env`
* Tidak mengizinkan input bebas untuk mencegah abuse API
* Tidak menggunakan bot WhatsApp karena tidak didukung resmi

---

## 🧠 Ingin Kontribusi?

Silakan buat Pull Request atau buka Issue jika kamu punya ide pengembangan:

* Integrasi Text-to-Speech untuk narasi konten
* Export ke video TikTok otomatis
* Upload langsung ke TikTok API (jika tersedia)

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).
