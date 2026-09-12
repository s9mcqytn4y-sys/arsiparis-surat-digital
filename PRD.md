# Product Requirement Document (PRD)

## Arsiparis Surat Digital — Sistem Manajemen Tata Usaha Perguruan Tinggi

### Arsitektur Multi-Unit Kerja (Rektorat · Fakultas · Program Studi · Lembaga / LPPM)

---

### Dokumen Kontrol

| Atribut | Detail Spesifikasi |
| :--- | :--- |
| **Nama Sistem** | Arsiparis Surat Digital |
| **Sub-Judul** | Sistem Manajemen Tata Usaha & Kearsipan Perguruan Tinggi |
| **Versi Dokumen** | 3.1.0 — University Edition (Production Spec) |
| **Status Dokumen** | Approved · Active Sprint Execution |
| **Target Instansi** | Universitas / Institut / Politeknik / Sekolah Tinggi |
| **Cakupan Organisasi** | Multi-Unit Kerja (Rektorat, Biro, Fakultas, Dekanat, Program Studi, LPPM, LPM) |
| **Runtime Target** | PHP 8.4.x (Strict Types Enabled) · Laravel 13.x · Node.js 22 LTS |
| **Frontend Stack** | Tailwind CSS v4 (Oxide) · Livewire 3.x · Alpine.js 3.x · Chart.js 4.x |
| **Komponen UI** | Filament v3 Engine (Table Builder, Form Builder, Action Modals) |
| **Database Dev** | SQLite 3.x (`database/database.sqlite`) |
| **Database Prod** | PostgreSQL 16.x (Strict Mode, ANSI SQL Compliant, Hostinger VPS) |
| **Penyimpanan Berkas**| Local Protected Storage (`storage/app/private/`) + Toggle S3/Cloudflare R2 |
| **Strategi Kunci ID** | UUID v7 (DB Primary Key) + Kode Register Bisnis Otomatis (`REG-SM/YYYY/MM/####`) |
| **Repositori & CI/CD** | GitHub Private (`s9mcqytn4y-sys/arsiparis-surat-digital`) via `gh cli` + GitHub Actions |

---

## 1. Domain Bisnis & Analisis Masalah Tata Usaha Kampus

Bagian Tata Usaha (TU) pada lingkungan perguruan tinggi mengelola alur birokrasi naskah dinas yang kompleks dan bertingkat:

- **Tingkat Universitas / Rektorat**: SK Rektor, Nota Dinas Pimpinan, Surat Perjanjian Kerjasama (MoU/MoA), surat edaran rektorat ke fakultas dan kementerian (Kemendikbudristek/Kemenag/LLDIKTI).
- **Tingkat Fakultas / Dekanat**: Surat pengantar dekan, SK Dekan, permohonan izin penelitian/magang mahasiswa, undangan rapat senat fakultas.
- **Tingkat Program Studi**: Surat keterangan aktif kuliah, pengantar tugas akhir/skripsi, naskah rekomendasi beasiswa.
- **Tingkat Lembaga / Biro**: LPPM (surat tugas pengabdian & penelitian), BAAK (administrasi akademik), BAUK (keuangan & kepegawaian).

### 1.1 Masalah Konkret yang Diselesaikan

1. **Konflik Penomoran Lintas Unit (*Race Condition*)**: Staf pada berbagai fakultas dan prodi sering mencatat naskah dinas secara bersamaan dengan buku agenda manual, memicu nomor surat ganda atau nomor lompat.
2. **Ketiadaan Visibilitas Lintas Unit**: Rektorat kesulitan melacak apakah disposisi surat penting dari LLDIKTI/Kementerian sudah sampai dan ditindaklanjuti oleh Dekan atau Kaprodi terkait.
3. **Dokumen Fisik Tersebar & Rentan Rusak**: Berkas akreditasi prodi, SK pengangkatan dosen, dan ijazah tersimpan di lemari arsip masing-masing fakultas tanpa repositori digital terpusat yang aman.
4. **Pencarian Lambat & Memakan Waktu**: Saat asesmen akreditasi BAN-PT/LAM, pencarian bukti fisik surat dan SK memakan waktu berhari-hari.
5. **Cetak Rekapitulasi Manual**: Penyusunan laporan bulanan beban naskah dinas universitas harus diketik ulang manual ke Excel.

---

## 2. Visi & Key Performance Indicators (KPI)

### 2.1 Visi Sistem

Menyediakan sistem tata usaha digital kampus modern yang mandiri, aman, dan terintegrasi untuk seluruh tingkatan unit kerja (Universitas, Fakultas, Prodi, Lembaga), dengan penomoran naskah dinas otomatis anti-duplikat, repositori berkas ber-UUID v7 terproteksi, pencarian instan multi-kriteria, serta pelaporan analitik siap cetak A4 berstandar naskah dinas perguruan tinggi.

### 2.2 Target KPI Operasional

| Indikator Kinerja | Target Sasaran | Mekanisme Pengukuran |
| :--- | :--- | :--- |
| **Kecepatan Entri Naskah** | $\le 45\text{ detik}$ per surat | Modal form reaktif Livewire + auto-fill metadata |
| **Duplikasi Nomor Surat** | $0\text{ insiden}$ (Nol Duplikasi) | DB Pessimistic Locking (`lockForUpdate`) + Unique DB Constraint |
| **Waktu Pencarian Berkas** | $\le 350\text{ ms}$ | Indeks komposit DB + Debounce input 300ms |
| **Integritas Dokumen Digital**| $100\%$ berkas tervalidasi | Inspeksi *Magic Bytes* header (%PDF-) & hash integritas |
| **Ketersediaan Sistem** | $\ge 99.9\%$ uptime | Hostinger VPS + backup berkala terverifikasi SHA-256 |
| **Kecepatan Siap Cetak A4** | $1\text{ klik}$ langsung rapi | Engine `@media print` dengan Kop Surat Resmi Universitas & Fakultas |

---

## 3. Matriks Peran & Hak Akses (RBAC Matrix Kampus)

Sistem mengadopsi 3 peran utama sesuai hirarki perguruan tinggi:

| Modul / Fungsi Sistem | Super Admin (Puskom/TI) | Petugas TU (Fakultas/Rektorat) | Pimpinan Unit (Rektor/Dekan/Kaprodi) |
| :--- | :---: | :---: | :---: |
| **Manajemen Unit Kerja** | CRUD Penuh | Baca Saja (Read-Only) | Baca Saja |
| **Manajemen Pegawai/Dosen** | CRUD Penuh | Baca Saja (Read-Only) | Baca Saja |
| **Konfigurasi Master Nomor Surat** | CRUD Penuh | Baca Saja (Pola Unit) | Baca Saja |
| **Surat Masuk (Registrasi & Disposisi)**| CRUD Penuh | CRUD Sesuai Unit Kerja | Monitoring & Beri Disposisi |
| **Surat Keluar (Generate & Terbit)**| CRUD Penuh | CRUD Sesuai Unit Kerja | Tinjau & Otorisasi / TTD |
| **Arsip Digital (SK & Dokumen)**| CRUD Penuh | CRUD Sesuai Unit Kerja | Akses Dokumen Unit |
| **Unduh / Streaming PDF Privat**| Ya (Signed URL) | Ya (Signed URL) | Ya (Signed URL) |
| **Dashboard Analitik & KPI** | Agregat Seluruh Kampus | Agregat Unit Kerja | Agregat Unit Kerja |
| **Cetak Laporan A4 & Ekspor Excel** | Ya (Semua Unit) | Ya (Unit Kerja) | Ya (Unit Kerja) |
| **Manajemen Akun Administrator** | Penuh | 403 Forbidden | 403 Forbidden |
| **Backup & Pemulihan Database** | Penuh | 403 Forbidden | 403 Forbidden |
| **Audit Trail (Log Aktivitas Mutasi)**| Penuh | 403 Forbidden | 403 Forbidden |

---

## 4. Arsitektur Teknis & Pola Desain (University Monolith)

```text
┌─────────────────────────────────────────────────────────────────────────┐
│              PERAMBAN KAMPUS (DESKTOP TU · TABLET DEKAN · MOBILE)       │
└────────────────────────────────────┬────────────────────────────────────┘
                                     │ HTTPS / TLS 1.3
┌────────────────────────────────────▼────────────────────────────────────┐
│                    PRESENTATION & UI COMPONENT TIER                     │
│  ├── Tailwind CSS v4 (Oxide Compiler) + Custom Theme Warm Slate         │
│  ├── Alpine.js 3.x (Dialog Transitions, Focus Trap, Dropdowns)          │
│  ├── Livewire 3 (Full-Page Components + Reactive Modal State)           │
│  ├── Filament v3 Engine (Table Builder, Form Builder, Action Modals)    │
│  └── Chart.js 4 (Canvas Tren Surat Kampus, Rasio Fakultas)              │
├─────────────────────────────────────────────────────────────────────────┤
│                    APPLICATION & DOMAIN LAYER (PHP 8.4)                 │
│  ├── Routing & Middleware (Auth, SecurityHeaders, SignedUrl, RoleGate)  │
│  ├── Actions (app/Actions/*: GenerateNomorSuratAction, UploadFileAction)│
│  ├── Enums (app/Enums/*: LevelUnitKerja, KategoriArsip, StatusDisposisi)│
│  ├── DTOs (app/DTOs/*: SuratMasukData, SuratKeluarData)                 │
│  ├── Storage Service (Magic Byte Validator, Private UUID Storage)       │
│  └── Stream Controller (Signed Route Validation & Chunked Streaming)    │
├─────────────────────────────────────────────────────────────────────────┤
│                    DATABASE & INFRASTRUCTURE TIER                       │
│  ├── Dual-Engine ORM: SQLite 3.x (Dev) ↔ PostgreSQL 16.x (Prod Hostinger)│
│  ├── Primary Keys: Time-ordered UUID v7 (`HasUuids`)                   │
│  ├── Concurrency Control: DB Transaction + Pessimistic lockForUpdate() │
│  ├── Audit Trail: Spatie Activitylog (Append-only immutable)           │
│  └── File Storage: Local Private Disk (VPS) + Modular S3/R2 Toggle     │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 5. Rincian Modul Fungsional

### 5.1 Modul Master Unit Kerja & Pegawai

- **Master Unit Kerja**: Menyimpan data Rektorat, Biro (BAAK, BAUK), Fakultas (Teknik, Ekonomi, Hukum), Program Studi (Teknik Informatika, Sistem Informasi), dan Lembaga (LPPM, LPM). Memiliki atribut kode unit (`FT`, `TI`, `REK`, `LPPM`), level unit, dan induk unit (*hierarchical self-relation*).
- **Master Pegawai & Pejabat**: Menyimpan NIDN/NIP, nama lengkap dengan gelar akademik, jabatan dinas (Rektor, Dekan, Wadek, Kaprodi, Kepala Biro, Staf TU), unit kerja, status aktif, dan tanda centang penandatangan resmi.

### 5.2 Modul Master Nomor Surat (Pola Penomoran Dinamis Kampus)

Mendukung pola penomoran resmi perguruan tinggi:

- Pola Universitas: `{nomor:03d}/UN{kode_kampus}/TU/{bulan_romawi}/{tahun}`
- Pola Fakultas: `{nomor:03d}/UN{kode_kampus}.{kode_fakultas}/TU/{bulan_romawi}/{tahun}`
- Pola Program Studi: `{nomor:03d}/UN{kode_kampus}.{kode_fakultas}.{kode_prodi}/AK/{bulan_romawi}/{tahun}`
- Pola SK Rektor: `SK/{nomor:03d}/UN{kode_kampus}/{tahun}`

### 5.3 Modul Surat Masuk (Inbound Letters)

- Registrasi otomatis dengan kode agenda internal: `REG-SM/YYYY/MM/####`.
- Pencatatan asal surat: Kementerian/LLDIKTI, Perguruan Tinggi Lain, Industri/Mitra, Mahasiswa, atau Internal Kampus.
- Unggah berkas pemindaian (Maks 10 MB, PDF murni dengan validasi *Magic Bytes* `%PDF-`).
- Alur disposisi: Dari pimpinan (Rektor/Dekan) ke pejabat pelaksana dengan instruksi tertulis dan batas waktu tindak lanjut.

### 5.4 Modul Surat Keluar (Outbound Letters)

- Mode penomoran otomatis dengan **Pessimistic Locking** (`lockForUpdate`) untuk mencegah duplikasi antar staf yang menginput serentak.
- Mode manual override terkontrol bila memerlukan penomoran susulan naskah dinas lama dengan validasi keunikan ketat.
- Unggah draf naskah dinas atau surat yang telah bertandatangan basah/elektronik.

### 5.5 Modul Arsip Digital Kampus

- Manajemen arsip digital selain surat harian: SK Rektor/Dekan, Dokumen Akreditasi Program Studi, Kurikulum & Silabus, Perjanjian Kerjasama (MoU/MoA), Berkas Ijazah & Transkrip.
- Pencarian cerdas berbasis kategori, unit kerja, nomor dokumen, dan tahun ketetapan.

### 5.6 Modul Laporan, Statistik & Cetak A4

- Filter laporan komprehensif: Rentang tanggal, unit kerja/fakultas, jenis naskah, dan status penyelesaian.
- Visualisasi grafik *Chart.js*: Tren persuratan bulanan universitas, perbandingan aktivitas antar fakultas, dan rasio kecepatan disposisi.
- Mesin Cetak A4 (`@media print`): Kop Surat Resmi Universitas & Fakultas dengan logo resmi, garis ganda dinas, dan blok tanda tangan pejabat terkait.
- Ekspor Tabular Excel (.xlsx): Format rapi dengan header instansi, styling border tipis, dan *auto-fit column*.

---

## 6. Rencana Sprint & Peta Jalan Eksekusi

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

> **Dokumen Kontrak**: Spesifikasi PRD v3.1.0 ini menggantikan seluruh spesifikasi sebelumnya dan menjadi acuan tunggal pengerjaan proyek sistem informasi Tata Usaha Perguruan Tinggi.
