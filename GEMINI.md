# GEMINI.md — Pedoman Utama & Aturan Pengembang (Developer Directives)

## Proyek: Arsiparis Surat Digital — Tata Usaha & Kearsipan Perguruan Tinggi

### Status: WAJIB DIPATUHI OLEH SETIAP AGEN AI & PENGEMBANG SELAMA SELURUH ITERASI

---

## 1. Identitas & Sasaran Proyek

Proyek ini bertujuan membangun **Arsiparis Surat Digital (University Edition)**, sistem tata usaha dan tata kelola naskah dinas digital terpusat untuk Perguruan Tinggi (Universitas, Institut, Politeknik, Sekolah Tinggi) yang melayani multi-unit kerja (Rektorat, Biro, Fakultas, Dekanat, Program Studi, LPPM, dan LPM). Sistem menggantikan buku agenda fisik dengan mekanisme penomoran otomatis anti-duplikat (*anti-race condition*), repositori dokumen digital ber-UUID v7 privat, pelacakan disposisi berjenjang, serta analitik dan laporan siap cetak A4 berstandar naskah dinas perguruan tinggi.

| Parameter Proyek | Nilai Baku |
| :--- | :--- |
| **Bahasa Pemrograman** | PHP 8.4+ (Enforce `declare(strict_types=1);` di baris pertama) |
| **Framework Web** | Laravel 13.x |
| **Frontend Reactive** | Livewire 3.x + Alpine.js 3.x + Tailwind CSS v4 (Oxide Engine) |
| **Komponen CRUD & Tabel**| Filament v3 Engine (Table Builder, Form Builder, Action Modals) |
| **Mesin Grafik** | Chart.js 4.x (Agregat naskah dinas rektorat & fakultas) |
| **Basis Data Pengembangan**| SQLite 3.x (`database/database.sqlite`) |
| **Basis Data Produksi** | PostgreSQL 16.x (Strict Mode, ANSI SQL, Hostinger VPS) |
| **Primary Key Standard** | UUID v7 (Time-ordered, anti-enumeration, `HasUuids`) |
| **Format Register Bisnis** | `REG-SM/YYYY/MM/####` (Masuk) & `REG-SK/YYYY/MM/####` (Keluar) |
| **Penyimpanan Berkas**| Local Protected Disk (`storage/app/private/`) + Toggle S3/R2 |
| **Paket RBAC & Audit** | `spatie/laravel-permission` + `spatie/laravel-activitylog` |
| **Pengujian Otomatis** | Pest v5 (Feature, Unit, Concurrency, Architecture Tests) |
| **Manajemen Versi & CI/CD**| Git + GitHub CLI (`gh`) + GitHub Actions (`.github/workflows/ci.yml`) |
| **Repositori GitHub** | `s9mcqytn4y-sys/arsiparis-surat-digital` (Private) |

---

## 2. Standar Rekayasa Perangkat Lunak (Coding Standards)

### 2.1 PHP 8.4 & Laravel 13 Directives

Setiap kode PHP yang ditulis ke dalam repositori ini WAJIB memenuhi:

1. **Strict Typing Mutlak**: Baris pertama setiap file PHP tanpa pengecualian:

   ```php
   <?php

   declare(strict_types=1);
   ```

2. **Type Hinting Penuh**: Semua argumen method/fungsi dan return type wajib dideklarasikan secara eksplisit (gunakan `void` jika tidak mengembalikan nilai).
3. **Constructor Property Promotion**: Selalu gunakan promosi properti konstruktor untuk *dependency injection* dan *class attributes*.
4. **Modern Eloquent Casts**: Selalu gunakan method `protected function casts(): array` di model Eloquent. DILARANG menggunakan properti warisan `protected $casts = [];`.
5. **Mass Assignment Protection**: Semua model wajib mendefinisikan `$fillable` secara eksplisit. Penggunaan `$guarded = []` atau `$guarded = ['id']` **DIHARAMKAN SECARA MUTLAK**.
6. **No `env()` Outside Config**: Dilarang memanggil fungsi `env()` di Controller, Livewire, Service, atau Model. Selalu gunakan `config('app.name')` agar kompatibel dengan *config caching* produksi.
7. **Pola Action / Service**: Logika bisnis kompleks (seperti penomoran surat, upload berkas terenkripsi, streaming PDF) wajib dipisahkan ke dalam kelas `app/Actions/` atau `app/Services/`. Controller hanya berfungsi menerima request dan memanggil Action.
8. **Form Request Validation**: Validasi HTTP wajib menggunakan kelas terdedikasi (`app/Http/Requests/*`). Jangan menulis aturan validasi mentah di dalam Controller.

### 2.2 Kompatibilitas Dual-Engine Database (SQLite Dev ↔ PostgreSQL 16 Prod Hostinger)

1. **Dilarang Sintaks SQL Spesifik Vendor**: Jangan menulis klausa raw SQL yang hanya ada di MySQL/MariaDB (seperti `IFNULL`, `GROUP_CONCAT` khusus, atau *backticks* `` `table` ``). Gunakan standar ANSI SQL atau helper Query Builder bawaan Eloquent.
2. **Primary Key UUID v7**: Gunakan `$table->uuid('id')->primary()` pada setiap tabel aplikasi.
3. **Pessimistic Locking**: Gunakan `DB::transaction()` dan `->lockForUpdate()` untuk penguncian baris saat meng-generate nomor surat urut agar tidak terjadi duplikasi saat multi-staf fakultas/rektorat mengklik bersamaan.
4. **Foreign Keys**: Selalu definisikan aksi penghapusan eksplisit: `cascadeOnDelete()` atau `restrictOnDelete()`.

---

## 3. Protokol Keamanan & Anti-Slop (Zero Trust Guardrails /007)

### 3.1 Validasi Berkas & Penyimpanan Privat (Hostinger VPS)

- **Penyimpanan Privat**: Seluruh berkas pindaian surat masuk, draf surat keluar, dan arsip kepegawaian disimpan di `storage/app/private/` (TIDAK BOLEH dibuatkan symlink publik `public/storage`).
- **Penamaan Acak UUID v7**: Berkas yang diunggah disimpan dengan nama UUID v7 acak untuk mencegah *Path Traversal* dan *File Overwrite*.
- **Magic Bytes Verification**: Sebelum memindahkan berkas, periksa byte header berkas nyata (`%PDF-` / `25 50 44 46` untuk PDF). Tolak berkas yang hanya mengganti ekstensi file.

### 3.2 Akses Dokumen Anti-IDOR (Signed Streaming Routes)

- Tidak ada dokumen yang bisa diakses langsung via URL statis publik.
- Akses berkas harus melalui `DocumentStreamController` yang memverifikasi:
  1. Tanda tangan URL yang sah dan belum kedaluwarsa (`URL::temporarySignedRoute()`, masa aktif 15 menit).
  2. Sesi pengguna terautentikasi dan otorisasi kebijakan unit kerja (*Policy Gate*).
  3. Header respons wajib menyertakan `X-Content-Type-Options: nosniff` dan `Content-Disposition: inline`.

### 3.3 Anti-Slop UI & Aksesibilitas

- Dilarang membuat antarmuka dengan gradien ungu/pink generik AI tanpa fungsi nyata.
- Pertahankan kontras warna minimal 4.5:1 (WCAG AA).
- Seluruh tabel laporan wajib memiliki pratinjau cetak A4 presisi (`@media print`) dengan kop surat resmi universitas & fakultas.

---

## 4. Alur Kerja Git & GitHub CLI (`gh`)

Repositori GitHub: `git@github.com:s9mcqytn4y-sys/arsiparis-surat-digital.git` (Private).

### 4.1 Konvensi Cabang (Branching Strategy)

- Cabang utama: `main` (selalu dalam keadaan stabil dan siap rilis).
- Cabang fitur: `feat/sprint-[nomor]-[nama-fitur]` (contoh: `feat/sprint-1-master-data`).
- Cabang perbaikan: `fix/[nama-bug]` (contoh: `fix/nomor-surat-lock`).

### 4.2 Alur Eksekusi Fitur via Terminal

```bash
# 1. Pastikan cabang main terbaru
git checkout main
git pull origin main

# 2. Buat cabang baru untuk sprint/fitur
git checkout -b feat/sprint-1-master-data

# 3. Jalankan linter & static analysis sebelum commit
vendor/bin/pint
php artisan test

# 4. Buat commit terstandarisasi (Conventional Commits)
git add .
git commit -m "feat(master-data): implement unit_kerja, pegawai and master nomor surat crud"

# 5. Push cabang dan buat Pull Request via gh cli
git push -u origin feat/sprint-1-master-data
gh pr create --title "feat(master-data): Implement Unit Kerja, Pegawai & Master Nomor Surat" --body "Menyelesaikan CRUD Multi-Unit Kerja, Pegawai, Master Nomor Surat Kampus, dan RBAC Spatie."

# 6. Merge ke main setelah lolos verifikasi
gh pr merge --merge --delete-branch
```

---

## 5. Rincian Eksekusi Sprint (End-to-End Roadmap Kampus)

```text
SPRINT 1: Foundation, Multi-Unit Data Master & RBAC (CURRENT SPRINT)
├── Setup Private GitHub Repo & CI/CD workflow (.github/workflows/ci.yml)
├── Setup .vscode configurations (settings, extensions, launch)
├── Migrasi Database UUID v7: unit_kerja, pegawai, master_nomor_surat, users, surat_masuk, surat_keluar, arsip_digital
├── Eloquent Models (Strict PHP 8.4, HasUuids, relations, modern casts)
├── Enums: LevelUnitKerja, KategoriArsip, StatusDisposisi
├── Seeders: Role & Permission (Spatie), Unit Kerja Kampus, Pegawai Pejabat, Master Pola Nomor
└── Automated Test Suite: Migrations, Models, & Role Verifications (Pest v5)

SPRINT 2: Core Letter Management & Anti-Race Condition
├── Livewire Components: Surat Masuk CRUD dengan validasi Magic Bytes PDF
├── Livewire Components: Surat Keluar CRUD dengan Pessimistic Lock Sequence Generator
├── Alur Disposisi Naskah Lintas Unit (Rektorat -> Dekanat -> Prodi)
├── DocumentStreamController (Signed streaming routes 15 menit, anti-IDOR)
└── Spatie Activitylog Integration (Audit trail mutasi naskah)

SPRINT 3: Digital Archive, University Analytics & Print Engine
├── Arsip Digital Kampus (Dokumen Akreditasi, SK, MoU dengan grid & filter unit)
├── Dashboard Livewire KPI Widgets (Metrik rektorat & fakultas realtime)
├── Chart.js Visualizations (Tren naskah bulanan, sebaran per fakultas)
├── Engine Cetak A4 (@media print Kop Surat Universitas & Fakultas ganda)
└── Ekspor Excel (.xlsx) dengan styling resmi dinas

SPRINT 4: Security Hardening & Backup Engine (/007 Protocol)
├── Rate Limiting Brute-Force Login (5x percobaan / 60 detik)
├── Middleware Security Headers & Content Security Policy (CSP)
├── Policy Gates (BOLA / IDOR ownership authorization checks)
├── Modul Cadangan (Backup) SQLite/Postgres dengan verifikasi checksum SHA-256
└── Pest Complete Test Suite (Unit, Feature, Concurrency, ArchTest)

SPRINT 5: Production Deployment on Hostinger VPS & Sign-Off
├── Konfigurasi Environment Produksi (PostgreSQL 16, Nginx, PHP 8.4 FPM)
├── Setup storage private symlink protection di VPS Hostinger
├── Seed data nyata universitas (Sample surat dinas rektorat & fakultas)
└── UAT Checklist & Final Production Sign-Off
```

---

> **Peringatan Keras**: Jangan melakukan perubahan skema atau arsitektur tanpa memperbarui dokumen `PRD.md`, `SRS.md`, dan `DESIGN.md`. Setiap baris kode yang dibuat harus lolos uji `Pest` dan linter `Pint`.
