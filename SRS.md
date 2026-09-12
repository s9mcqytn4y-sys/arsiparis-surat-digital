# Software Requirements Specification (SRS)

## Arsiparis Surat Digital — Tata Usaha & Kearsipan Perguruan Tinggi

### Berbasis Standar ISO/IEC/IEEE 29148 & Kaidah Naskah Dinas Pendidikan Tinggi

---

### Dokumen Kontrol

| Atribut | Spesifikasi Formal |
| :--- | :--- |
| **Nama Sistem** | Arsiparis Surat Digital |
| **Kode Identifikasi** | ASD-SRS-2026-UNIV |
| **Versi Dokumen** | 3.1.0 — University Production Specification |
| **Status Dokumen** | Ratified & Approved for Implementation |
| **Target Organisasi** | Tata Usaha Perguruan Tinggi (Rektorat, Fakultas, Program Studi, LPPM) |
| **Arsitektur Inti** | Modular Monolith (Laravel 13.x · Livewire 3 · Filament Engine v3) |
| **Runtime Target** | PHP 8.4.x (Strict Types Enabled) · Node.js 22 LTS |
| **Database Engine** | **Dev**: SQLite 3.x · **Prod**: PostgreSQL 16.x (ANSI SQL, Hostinger VPS) |
| **Identitas Entitas** | UUID v7 (`HasUuids`) + Register Bisnis Otomatis (`REG-SM/YYYY/MM/####`) |
| **Penyimpanan Berkas**| Local Disk Terproteksi (`storage/app/private/`) + Toggle S3/R2 |
| **Protokol Keamanan** | Zero Trust Architecture · OWASP Top 10 2026 · UU Perlindungan Data Pribadi |

---

## 1. Pendahuluan & Lingkup Sistem Perguruan Tinggi

### 1.1 Tujuan Dokumen

Dokumen Spesifikasi Kebutuhan Perangkat Lunak (*Software Requirements Specification* - SRS) ini menetapkan batasan fungsional, non-fungsional, relasi data ber-UUID v7, arsitektur teknis, dan rancangan pengujian untuk **Arsiparis Surat Digital**. Sistem dirancang melayani tata kelola naskah dinas universitas/institut lintas unit kerja secara terpusat, aman, dan bebas dari *race condition*.

### 1.2 Lingkup Organisasi Multi-Unit

Sistem mengakomodasi hirarki perguruan tinggi berjenjang:

1. **Tingkat Universitas / Rektorat**: Mengelola Surat Keputusan (SK Rektor), Surat Perjanjian Kerjasama (MoU/MoA), Nota Dinas Pimpinan, dan persuratan ke Kementerian/LLDIKTI.
2. **Tingkat Biro & Lembaga**: Biro Administrasi Akademik (BAAK), Biro Umum & Kepegawaian (BAUK), Lembaga Penelitian & Pengabdian Masyarakat (LPPM), Lembaga Penjaminan Mutu (LPM).
3. **Tingkat Fakultas / Dekanat**: Persuratan Dekan, Wakil Dekan, surat pengantar magang/penelitian, undangan rapat dewan pertimbangan fakultas.
4. **Tingkat Program Studi**: Surat keterangan akademik mahasiswa, permohonan penguji skripsi/thesis, dan arsip borang akreditasi.

---

## 2. Matriks Hak Akses & Peran Kampus (RBAC Matrix)

| Modul & Hak Akses | Super Admin (Puskom) | Petugas TU (Unit/Fakultas) | Pimpinan Unit (Rektor/Dekan/Kaprodi) |
| :--- | :---: | :---: | :---: |
| Autentikasi & Profil | Hak Penuh | Hak Penuh | Hak Penuh |
| Dashboard KPI Kampus | Seluruh Universitas | Spesifik Unit Kerja | Spesifik Unit Kerja |
| Master Unit Kerja (CRUD) | Hak Penuh | Baca Saja (Read-Only) | Baca Saja |
| Master Pegawai/Dosen (CRUD)| Hak Penuh | Baca Saja (Read-Only) | Baca Saja |
| Master Nomor Surat (Pola) | Hak Penuh | Baca Saja (Pola Unit) | Baca Saja |
| Surat Masuk (Input & Edit) | Hak Penuh | CRUD Unit Kerja | Baca & Beri Disposisi |
| Surat Keluar (Generate & Terbit)| Hak Penuh | CRUD Unit Kerja | Verifikasi & TTD |
| Arsip Digital (SK/Akreditasi)| Hak Penuh | CRUD Unit Kerja | Akses Dokumen Unit |
| Unduh / Stream Berkas Privat | Ya (Signed URL) | Ya (Signed URL) | Ya (Signed URL) |
| Laporan & Cetak A4 / Excel | Ya (Semua Unit) | Ya (Unit Kerja) | Ya (Unit Kerja) |
| Manajemen Akun Pengguna | Hak Penuh | 403 Forbidden | 403 Forbidden |
| Backup & Restore Database | Hak Penuh | 403 Forbidden | 403 Forbidden |
| Log Audit (Activity Logs) | Hak Penuh | 403 Forbidden | 403 Forbidden |

---

## 3. Spesifikasi Basis Data (Data Model & Schema Perguruan Tinggi)

### 3.1 Strategi Kompatibilitas Dual-Engine: SQLite 3.x (Dev) vs PostgreSQL 16.x (Prod Hostinger)

1. **Primary Key Standard**: Menggunakan **UUID v7** terurut waktu (`$table->uuid('id')->primary()`). Menghilangkan disparitas auto-increment SQLite vs Postgres `SERIAL/BIGSERIAL` dan mencegah serangan enumerasi (*BOLA/IDOR*).
2. **Kunci Bisnis Terformat (Business Register ID)**:
   - Surat Masuk: `REG-SM/{tahun}/{bulan:02d}/{nomor_urut:04d}` (Contoh: `REG-SM/2026/09/0001`).
   - Surat Keluar: `REG-SK/{tahun}/{bulan:02d}/{nomor_urut:04d}` (Contoh: `REG-SK/2026/09/0001`).
3. **ANSI SQL Compliance**: Dilarang menggunakan fungsi non-standar MySQL (misal `NOW()`, `IFNULL`, backticks). Gunakan syntax Query Builder standar Eloquent.
4. **Foreign Key Deletion Actions**: Wajib mendefinisikan `cascadeOnDelete()` atau `restrictOnDelete()` secara eksplisit.
5. **Indeks Komposit**: Diterapkan pada pasangan kolom pencarian tinggi: `(unit_kerja_id, tanggal_surat)`, `(kode_klasifikasi, tahun)`.

### 3.2 Kamus Data & Struktur Tabel

#### 1. Tabel `unit_kerja`

Menyimpan struktur organisasi kampus (Rektorat, Fakultas, Program Studi, Lembaga, Biro).

| Nama Kolom | Tipe Data | Constraint / Keterangan |
| :--- | :--- | :--- |
| `id` | `UUID` | Primary Key (UUID v7) |
| `parent_id` | `UUID` | NULLABLE, Foreign Key `unit_kerja(id)` on delete set null |
| `kode_unit` | `VARCHAR(30)` | NOT NULL, UNIQUE, index (misal: `REK`, `FT`, `TI`, `LPPM`) |
| `nama_unit` | `VARCHAR(150)`| NOT NULL (misal: "Fakultas Teknik", "Prodi Informatika") |
| `level` | `VARCHAR(30)` | NOT NULL, Enum: `universitas`, `fakultas`, `prodi`, `lembaga`, `biro` |
| `singkatan` | `VARCHAR(50)` | NULLABLE (misal: "FT", "TI", "LPPM") |
| `is_active` | `BOOLEAN` | NOT NULL, DEFAULT `true` |
| `created_at` | `TIMESTAMP` | NOT NULL |
| `updated_at` | `TIMESTAMP` | NOT NULL |

#### 2. Tabel `users`

Menyimpan akun operator dan pimpinan dengan asosiasi unit kerja.

| Nama Kolom | Tipe Data | Constraint / Keterangan |
| :--- | :--- | :--- |
| `id` | `UUID` | Primary Key (UUID v7) |
| `unit_kerja_id` | `UUID` | NULLABLE, Foreign Key `unit_kerja(id)` on delete restrict |
| `name` | `VARCHAR(100)` | NOT NULL, Nama operator/pejabat |
| `username` | `VARCHAR(50)` | NOT NULL, UNIQUE, index untuk login |
| `email` | `VARCHAR(100)` | NOT NULL, UNIQUE |
| `password` | `VARCHAR(255)` | NOT NULL, Hash Argon2id/Bcrypt ($\ge 12$) |
| `role` | `VARCHAR(30)` | NOT NULL, DEFAULT `'petugas'`, enum: `admin`, `petugas`, `pimpinan` |
| `is_active` | `BOOLEAN` | NOT NULL, DEFAULT `true` |
| `remember_token`| `VARCHAR(100)`| NULLABLE |
| `created_at` | `TIMESTAMP` | NOT NULL |
| `updated_at` | `TIMESTAMP` | NOT NULL |

#### 3. Tabel `pegawai`

Menyimpan data dosen, pimpinan dan staf tenaga kependidikan (Tendik).

| Nama Kolom | Tipe Data | Constraint / Keterangan |
| :--- | :--- | :--- |
| `id` | `UUID` | Primary Key (UUID v7) |
| `unit_kerja_id` | `UUID` | NOT NULL, Foreign Key `unit_kerja(id)` on delete restrict |
| `nip_nidn` | `VARCHAR(30)` | NULLABLE, UNIQUE, index (NIP Tendik / NIDN Dosen) |
| `nama` | `VARCHAR(150)`| NOT NULL, Nama lengkap dengan gelar akademik |
| `jabatan` | `VARCHAR(100)`| NOT NULL (Rektor, Dekan, Wadek I, Kaprodi, KTU) |
| `golongan` | `VARCHAR(50)` | NULLABLE (Penata Tk. I / IV-a, dst) |
| `no_telepon` | `VARCHAR(25)` | NULLABLE, Nomor WhatsApp resmi |
| `is_active` | `BOOLEAN` | NOT NULL, DEFAULT `true` |
| `created_at` | `TIMESTAMP` | NOT NULL |
| `updated_at` | `TIMESTAMP` | NOT NULL |

#### 4. Tabel `master_nomor_surat`

Menyimpan pola penomoran naskah dinas per unit kerja dan tahun kalender.

| Nama Kolom | Tipe Data | Constraint / Keterangan |
| :--- | :--- | :--- |
| `id` | `UUID` | Primary Key (UUID v7) |
| `unit_kerja_id` | `UUID` | NOT NULL, Foreign Key `unit_kerja(id)` on delete cascade |
| `kode_klasifikasi`| `VARCHAR(50)` | NOT NULL (misal: `PK.01` Akademik, `KP.02` Kepegawaian) |
| `nama_klasifikasi`| `VARCHAR(120)`| NOT NULL (Keterangan klasifikasi naskah) |
| `format_pola` | `VARCHAR(150)`| NOT NULL, misal: `{nomor:03d}/UN99.{kode_unit}/TU/{bulan_romawi}/{tahun}` |
| `nomor_terakhir` | `INTEGER` | NOT NULL, DEFAULT `0`, diupdate via `lockForUpdate()` |
| `tahun` | `INTEGER` | NOT NULL, Tahun berlakunya pola |
| `keterangan` | `TEXT` | NULLABLE |
| `created_at` | `TIMESTAMP` | NOT NULL |
| `updated_at` | `TIMESTAMP` | NOT NULL |
| *Index Unik* | `UNIQUE` | `(unit_kerja_id, kode_klasifikasi, tahun)` |

#### 5. Tabel `surat_masuk`

Menyimpan seluruh naskah dinas yang masuk ke universitas, fakultas, atau prodi.

| Nama Kolom | Tipe Data | Constraint / Keterangan |
| :--- | :--- | :--- |
| `id` | `UUID` | Primary Key (UUID v7) |
| `unit_kerja_id` | `UUID` | NOT NULL, Foreign Key `unit_kerja(id)` on delete restrict |
| `nomor_agenda` | `VARCHAR(50)` | NOT NULL, index (format: `REG-SM/YYYY/MM/####`) |
| `nomor_surat` | `VARCHAR(120)`| NOT NULL, Nomor surat resmi instansi pengirim |
| `pengirim` | `VARCHAR(150)`| NOT NULL, Lembaga/Instansi pengirim |
| `tanggal_surat` | `DATE` | NOT NULL |
| `tanggal_terima` | `DATE` | NOT NULL |
| `perihal` | `VARCHAR(255)`| NOT NULL, Pokok isi surat |
| `ringkasan` | `TEXT` | NULLABLE |
| `disposisi_kepada`| `UUID` | NULLABLE, Foreign Key ke `pegawai(id)` |
| `instruksi_disposisi`| `TEXT` | NULLABLE |
| `status_disposisi`| `VARCHAR(30)` | NOT NULL, DEFAULT `'menunggu'`, enum: `menunggu`, `diproses`, `selesai` |
| `file_path` | `VARCHAR(255)`| NULLABLE, Path penyimpanan privat UUID v7 |
| `file_mime` | `VARCHAR(50)` | NULLABLE (`application/pdf`) |
| `file_size` | `INTEGER` | NULLABLE |
| `created_by` | `UUID` | NOT NULL, Foreign Key `users(id)` |
| `created_at` | `TIMESTAMP` | NOT NULL, index |
| `updated_at` | `TIMESTAMP` | NOT NULL |

#### 6. Tabel `surat_keluar`

Menyimpan naskah dinas resmi yang diterbitkan unit kerja kampus.

| Nama Kolom | Tipe Data | Constraint / Keterangan |
| :--- | :--- | :--- |
| `id` | `UUID` | Primary Key (UUID v7) |
| `unit_kerja_id` | `UUID` | NOT NULL, Foreign Key `unit_kerja(id)` on delete restrict |
| `nomor_agenda` | `VARCHAR(50)` | NOT NULL, index (format: `REG-SK/YYYY/MM/####`) |
| `nomor_surat` | `VARCHAR(150)`| NOT NULL, UNIQUE, index, nomor resmi hasil generator |
| `kode_klasifikasi`| `VARCHAR(50)` | NOT NULL |
| `tujuan` | `VARCHAR(150)`| NOT NULL, Instansi / Mitra / Pihak tujuan |
| `tanggal_surat` | `DATE` | NOT NULL |
| `perihal` | `VARCHAR(255)`| NOT NULL |
| `ringkasan` | `TEXT` | NULLABLE |
| `jenis_surat`| `VARCHAR(100)` | NOT NULL |
| `file_path` | `VARCHAR(255)`| NULLABLE |
| `created_by` | `UUID` | NOT NULL, Foreign Key `users(id)` |
| `created_at` | `TIMESTAMP` | NOT NULL, index |
| `updated_at` | `TIMESTAMP` | NOT NULL |

#### 7. Tabel `arsip_digital`

Menyimpan berkas permanen (SK Rektor/Dekan, Akreditasi, Ijazah, Kurikulum, Kerjasama).

| Nama Kolom | Tipe Data | Constraint / Keterangan |
| :--- | :--- | :--- |
| `id` | `UUID` | Primary Key (UUID v7) |
| `unit_kerja_id` | `UUID` | NOT NULL, Foreign Key `unit_kerja(id)` on delete restrict |
| `judul` | `VARCHAR(200)`| NOT NULL, Judul naskah arsip |
| `kategori` | `VARCHAR(50)` | NOT NULL, Enum: `sk_rektor`, `sk_dekan`, `akreditasi`, `kurikulum`, `kerjasama`, `kepegawaian`, `keuangan`, `lainnya` |
| `nomor_dokumen` | `VARCHAR(120)`| NULLABLE, Nomor ketetapan/SK |
| `tanggal_dokumen`| `DATE` | NOT NULL |
| `deskripsi` | `TEXT` | NULLABLE |
| `file_path` | `VARCHAR(255)`| NOT NULL |
| `file_mime` | `VARCHAR(50)` | NOT NULL |
| `file_size` | `INTEGER` | NOT NULL |
| `pegawai_id` | `UUID` | NULLABLE, Relasi opsional ke `pegawai(id)` |
| `created_by` | `UUID` | NOT NULL, Foreign Key `users(id)` |
| `created_at` | `TIMESTAMP` | NOT NULL |
| `updated_at` | `TIMESTAMP` | NOT NULL |

---

## 4. Keamanan & Kebijakan Zero Trust Kampus (/007 Standard)

1. **Storage Privat & Tanpa Symlink Publik**:
   - Seluruh berkas pindaian disimpan di `storage/app/private/` pada VPS Hostinger.
   - Tidak ada tautan simbolik publik (`public/storage`) ke direktori naskah dinas.
2. **Signed Streaming Route Controller**:
   - Berkas diakses via `DocumentStreamController` yang memvalidasi tanda tangan kriptografis URL (`URL::temporarySignedRoute()`, TTL 15 menit).
   - Otorisasi berbasis kebijakan kepemilikan naskah (*BOLA/IDOR protection*): Staf hanya dapat melihat dokumen unit kerjanya kecuali peran `super_admin`.
3. **Magic Bytes Verification**:
   - Sistem memeriksa byte header file (`%PDF-` / `25 50 44 46`). Ekstensi file yang dimanipulasi akan langsung ditolak dengan HTTP 422.
4. **Perlindungan Brute-Force**:
   - Throttling 5 percobaan gagal per 60 detik pada endpoint `/login` dengan lockout responsif.

---

> **Status Kelulusan SRS**: Dokumen SRS v3.1.0 ini mencerminkan arsitektur sistem informasi Tata Usaha Perguruan Tinggi multi-unit kerja berbasis UUID v7 dan siap diimplementasikan.
