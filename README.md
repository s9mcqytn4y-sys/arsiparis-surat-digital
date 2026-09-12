# Arsiparis Surat Digital (University Edition) 🎓

Sistem Tata Usaha dan Tata Kelola Naskah Dinas Digital Terpusat untuk Perguruan Tinggi (Universitas, Institut, Politeknik, Sekolah Tinggi) berbasis **Laravel 13.x** dan **PHP 8.4+**.

Sistem ini dirancang khusus untuk mengelola tata kelola persuratan dinas multi-unit (Rektorat, Biro, Fakultas, Dekanat, Program Studi, LPPM, LPM) dengan nomor register otomatis anti-duplikasi (*pessimistic lock*), repositori dokumen digital ber-UUID v7 privat, pelacakan disposisi berjenjang, dan mesin cetak A4 berstandar naskah dinas universitas.

## 🌟 Tech Stack

- **Bahasa Pemrograman**: PHP 8.4+ (`declare(strict_types=1);` mutlak)
- **Framework Web**: Laravel 13.x
- **Frontend Reactive**: Livewire 3.x + Alpine.js 3.x + Tailwind CSS v4 (Oxide Engine)
- **Komponen CRUD & Tabel**: Filament v3 Engine
- **Mesin Grafik**: Chart.js 4.x
- **Basis Data Pengembangan**: SQLite 3.x (`database/database.sqlite`)
- **Basis Data Produksi**: PostgreSQL 16.x (Strict Mode, ANSI SQL, Hostinger VPS)
- **Primary Key Standard**: UUID v7 (Time-ordered, anti-enumeration)
- **Format Register Bisnis**: `REG-SM/YYYY/MM/####` (Masuk) & `REG-SK/YYYY/MM/####` (Keluar)
- **Penyimpanan Berkas**: Protected Local Disk (`storage/app/private/`) + Signed Streaming Routes (15 Menit)
- **RBAC & Audit Trail**: `spatie/laravel-permission` + `spatie/laravel-activitylog`
- **Pengujian Otomatis**: Pest v5 (Feature, Unit, Concurrency, Arch Tests)
- **CI/CD Pipeline**: GitHub Actions (`.github/workflows/ci.yml`)

## 🏛️ Struktur Multi-Unit Kerja Kampus

```text
Universitas (Rektorat)
├── Biro Akademik & Kemahasiswaan (BAAK)
├── Biro Administrasi Umum & Keuangan (BAUK)
├── Lembaga Penelitian & Pengabdian Masyarakat (LPPM)
├── Lembaga Penjaminan Mutu (LPM)
└── Fakultas (Dekanat)
    ├── Program Studi Teknik Informatika
    ├── Program Studi Sistem Informasi
    └── Tata Usaha Fakultas
```

## 🛠️ Panduan Memulai Cepat (Local Development)

```bash
# 1. Clone repositori
git clone git@github.com:s9mcqytn4y-sys/arsiparis-surat-digital.git
cd arsiparis-surat-digital

# 2. Pasang dependensi PHP & Node
composer install
npm install

# 3. Konfigurasi berkas lingkungan
cp .env.example .env
php artisan key:generate

# 4. Jalankan migrasi & seeder master data kampus
php artisan migrate:fresh --seed

# 5. Jalankan pengujian otomatis (Pest v5)
php artisan test

# 6. Jalankan server lokal
php artisan serve
npm run dev
```

## 📜 Kepatuhan & Arsitektur

Panduan lengkap mengenai arsitektur, konvensi penamaan, protokol keamanan Zero Trust (/007), serta aturan rekayasa perangkat lunak wajib merujuk ke:

- `GEMINI.md`: Pedoman Utama & Aturan Pengembang.
- `PRD.md`: Product Requirement Document (University Edition).
- `SRS.md`: Software Requirements Specification & Schema Database.
- `DESIGN.md`: University Design System & Component Guidelines.

## 📄 Lisensi

Perangkat lunak ini dilisensikan di bawah lisensi kepemilikan terbatas untuk operasional Perguruan Tinggi.
