# Portfolio Bahtiar Rifai

Website portfolio pribadi, dibangun dengan **Laravel 12**, **Inertia.js**, **Vue 3**, dan **Tailwind CSS v4**.
Responsif (mobile, tablet, desktop), mendukung mode gelap/terang, dan punya form kontak yang tersimpan ke database.

## Menjalankan di Laragon

1. Salin folder ini ke `C:\laragon\www\portfolio`, lalu buka **Terminal** Laragon di folder tersebut.
2. Install dependency:
   ```bash
   composer install
   npm install
   ```
3. Siapkan environment:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```
4. Atur database di `.env`. Default memakai SQLite; untuk MySQL Laragon ubah menjadi:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=portfolio
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Buat database `portfolio` lewat HeidiSQL / phpMyAdmin terlebih dahulu.
5. Jalankan migration:
   ```bash
   php artisan migrate
   ```
6. Jalankan aplikasi (dua terminal):
   ```bash
   npm run dev
   php artisan serve
   ```
   Buka http://127.0.0.1:8000 atau http://portfolio.test (auto virtual host Laragon).

Untuk production: `npm run build`, lalu set `APP_ENV=production` dan `APP_DEBUG=false`.

## Halaman

| URL | Halaman | File Vue | Data di `config/portfolio.php` |
| --- | --- | --- | --- |
| `/` | Beranda | `Pages/Home.vue` | `profile`, `stats`, `projects` (featured), `stack` |
| `/tentang` | Tentang | `Pages/About.vue` | `profile.bio`, `architecture`, `stack`, `journey` |
| `/project` | Daftar project | `Pages/Projects/Index.vue` | `projects` |
| `/project/{slug}` | Detail project | `Pages/Projects/Show.vue` | `projects` |
| `/pengalaman` | Pengalaman | `Pages/Experience.vue` | `experience` |
| `/sertifikat` | Sertifikat | `Pages/Certificates.vue` | `certificates` |
| `/kontak` | Kontak | `Pages/Contact.vue` | `profile.socials` |

Menu navbar diatur di `resources/js/navigation.js`.

## Mengubah isi website

Semua konten ada di **`config/portfolio.php`**. Tidak perlu mengubah file Vue untuk mengganti teks.
Entri pengalaman dan sertifikat yang bertanda `'example' => true` masih contoh dan tampil dengan label
"Contoh"; ganti isinya lalu ubah menjadi `false`, atau hapus entrinya.

- **Foto profil**: taruh file di `public/images/profile.jpg`, lalu isi `'photo' => '/images/profile.jpg'`.
- **CV**: taruh file di `public/files/`, lalu isi `'cv' => '/files/nama-file.pdf'` (tombol "Unduh CV" muncul otomatis).
- **Ikon tech stack**: nama ikon mengikuti [simple-icons](https://simpleicons.org). Jika memakai ikon baru,
  tambahkan import-nya di `resources/js/Components/TechIcon.vue`.
- **Link repo / demo project**: isi `'url'` atau `'demo'` pada entri project di `projects`.

Setelah mengubah `config/portfolio.php` di production, jalankan `php artisan config:clear`.

## Struktur

```
app/Http/Controllers/PageController.php          Semua halaman (data dari config)
app/Http/Controllers/ContactController.php       Simpan pesan dari form kontak
app/Http/Requests/StoreContactMessageRequest.php Validasi form kontak
app/Http/Middleware/HandleInertiaRequests.php    Shared props (flash message)
app/Models/ContactMessage.php
config/portfolio.php                             Semua konten website
resources/js/Layouts/SiteLayout.vue              Navbar + footer untuk semua halaman
resources/js/Pages/                              Satu file per halaman
resources/js/Components/                         Potongan UI yang dipakai ulang
resources/css/app.css                            Tema warna (terang & gelap) + Tailwind v4
```

## Membaca pesan dari form kontak

Pesan tersimpan di tabel `contact_messages`. Cara cepat melihatnya:

```bash
php artisan tinker
>>> App\Models\ContactMessage::latest()->get(['name', 'email', 'subject', 'created_at']);
```

Form dibatasi 5 kiriman per menit per IP untuk mencegah spam.
