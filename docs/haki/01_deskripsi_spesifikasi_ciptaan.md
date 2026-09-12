# DOKUMEN SPESIFIKASI TEKNIS & DESKRIPSI CIPTAAN
## PENDAFTARAN HAK CIPTA PROGRAM KOMPUTER — DJKI KEMENKUMHAM RI

---

### I. IDENTITAS CIPTAAN

| Parameter Pendaftaran | Data Keterangan |
| :--- | :--- |
| **Judul Ciptaan** | Sistem Tata Usaha & Kearsipan Naskah Dinas Digital Perguruan Tinggi Terintegrasi (Arsiparis Surat Digital) |
| **Jenis Ciptaan** | Program Komputer / Aplikasi Web Tata Kelola Kearsipan |
| **Sektor Penggunaan** | Administrasi Akademik, Tata Usaha Universitas, Biro Rektorat, Fakultas, Lembaga Penelitian & Pengabdian (LPPM), dan Satuan Penjaminan Mutu (LPM) |
| **Tahun Pembuatan** | 2026 |
| **Negara Pembuatan** | Indonesia |
| **Format Distribusi** | Web-Based SaaS & On-Premise Institutional Deployment |

---

### II. LATAR BELAKANG & URGENSI INOVASI

Proses ketatausahaan dan tata kelola persuratan di lingkungan perguruan tinggi di Indonesia secara historis menghadapi kendala:
1. **Pencatatan Buku Agenda Manual**: Rawan hilangnya fisik lembar agenda, pencatatan ganda, dan manipulasi urutan penanggalan surat.
2. ***Race Condition* Penomoran Surat**: Multi-staf fakultas dan biro yang menerbitkan naskah dinas dalam waktu bersamaan kerap menghasilkan nomor surat kembar (*duplicate letter reference numbers*).
3. **Penyimpanan Berkas Tidak Terisolasi**: File pindaian PDF yang disimpan di direktori publik rentan terhadap eksploitasi *Insecure Direct Object Reference* (IDOR) dan *Path Traversal*.
4. **Alur Disposisi Terputus**: Kesulitan melacak posisi naskah dinas berjenjang (dari Rektorat ke Dekanat, lalu ke Program Studi) serta lambatnya pemantauan tenggat waktu disposisi.

Sistem **Arsiparis Surat Digital** dirancang secara khusus untuk memecahkan seluruh kendala tersebut dengan memanfaatkan arsitektur rekayasa perangkat lunak modern, isolasi penyimpanan zero-trust, dan penguncian transaksi basis data deterministik.

---

### III. FITUR UTAMA & SPESIFIKASI TEKNOLOGI

#### 1. Arsitektur Multi-Unit Kerja Hierarkis
- Mendukung pemetaan unit kerja berjenjang:
  - Level 1: Universitas / Rektorat
  - Level 2: Biro & Lembaga (BAAK, LPPM, LPM, BAU)
  - Level 3: Fakultas / Dekanat
  - Level 4: Program Studi / Laboratorium
- Hak akses tersinkronisasi berbasis *Role-Based Access Control* (RBAC) dengan pemisahan wewenang Administrator Kampus, Staf Tata Usaha, dan Pimpinan Unit Kerja.

#### 2. Generator Nomor Surat Otomatis Anti-Race Condition
- Menggunakan mekanisme *pessimistic locking* (`DB::transaction()` + `lockForUpdate()`) pada tabel master pola nomor surat.
- Menjamin nomor urut surat, nomor agenda (`REG-SM/YYYY/MM/####` & `REG-SK/YYYY/MM/####`), kode klasifikasi naskah dinas (misal: `KP.01`, `HM.02`), angka romawi bulan berjalan, dan tahun kalender diterbitkan secara atomik tanpa duplikasi.

#### 3. Repositori Dokumen Digital Terisolasi (/007 Zero Trust)
- Berkas pindaian surat masuk, draf surat keluar, dan arsip digital disimpan secara privat di `storage/app/private/` dengan penamaan acak **UUID v7** (Universally Unique Identifier versi 7 berbasis urutan waktu).
- Dilengkapi validasi *Magic Bytes* header berkas (`%PDF-` / `25 50 44 46`) sebelum berkas diizinkan disimpan ke storage.
- Akses berkas melalui rute streaming bertanda tangan dinamis (`URL::temporarySignedRoute()`) dengan masa kedaluwarsa 15 menit, mencegah akses URL publik langsung dan serangan IDOR.

#### 4. Disposisi Naskah Dinas Berjenjang
- Pelacakan alur lembar disposisi dari pengirim (pejabat pemberi instruksi) kepada pejabat/staf penerima disposisi.
- Dilengkapi atribut instruksi dinas, catatan tindak lanjut, tenggat waktu (*deadline*), dan status berjenjang (`Menunggu`, `Sedang Diproses`, `Selesai`).

#### 5. Mesin Cetak Standar Naskah Dinas & Ekspor Data
- Templat cetak A4 siap cetak (`@media print`) yang dilengkapi Kop Surat Resmi Perguruan Tinggi, logo institusi, nomor registrasi, dan format tanda tangan dinas.
- Fitur ekspor basis data Master Data dan Laporan ke format CSV dengan penandaan **UTF-8 BOM** (`\xEF\xBB\xBF`) agar tabel terbaca sempurna di Microsoft Excel Indonesia.
- Fitur pencadangan data (*backup snapshot*) terenkapsulasi JSON dengan verifikasi integritas checksum **SHA-256**.

---

### IV. STACK TEKNOLOGI

```text
┌────────────────────────────────────────────────────────────────────────┐
│                        ARSIPARIS SURAT DIGITAL                         │
├───────────────────┬────────────────────────────────────────────────────┤
│ Komponen          │ Spesifikasi Teknologi                              │
├───────────────────┼────────────────────────────────────────────────────┤
│ Bahasa Inti       │ PHP 8.4+ (Enforced declare(strict_types=1))        │
│ Web Framework     │ Laravel 13.x                                       │
│ Reactive Frontend │ Livewire 3.x + Alpine.js 3.x                       │
│ CSS Framework     │ Tailwind CSS v4 (Oxide High-Performance Engine)    │
│ Primary Key       │ UUID v7 (Time-Ordered 128-bit)                     │
│ Database Dev/Test │ SQLite 3.x In-Memory & File                        │
│ Database Prod     │ PostgreSQL 16.x (Strict Mode, ANSI SQL)            │
│ Security Guard    │ Magic Bytes Verification + Signed URLs + SHA-256   │
│ Cloud Deployment  │ Vercel Serverless / Hostinger VPS (Nginx + PHP-FPM)│
└───────────────────┴────────────────────────────────────────────────────┘
```
