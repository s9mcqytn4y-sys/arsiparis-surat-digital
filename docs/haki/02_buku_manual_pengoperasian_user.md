# BUKU MANUAL PENGOPERASIAN PENGGUNA (USER MANUAL)
## ARSIPARIS SURAT DIGITAL — TATA USAHA & KEARSIPAN PERGURUAN TINGGI

---

### BAB I: PENDAHULUAN

Buku panduan ini disusun sebagai manual operasional resmi bagi staf tata usaha, arsiparis, sekretaris dekanat/rektorat, dan pimpinan perguruan tinggi dalam mengoperasikan sistem **Arsiparis Surat Digital**.

---

### BAB II: AKSES & AUTENTIKASI SISTEM

1. **Membuka Sistem**:
   - Buka peramban (*web browser*) modern (Google Chrome, Mozilla Firefox, Microsoft Edge, atau Safari).
   - Masukkan alamat domain aplikasi: `https://[domain-kampus].ac.id` atau URL demo Vercel.
2. **Login Pengguna**:
   - Masukkan **Email Resmi Institusi** (contoh: `admin@universitas.ac.id`).
   - Masukkan **Kata Sandi**.
   - Klik tombol **Masuk ke Sistem**.
   - *Catatan Keamanan*: Sistem dilengkapi proteksi *Brute-Force Rate Limiting* (maksimal 5 kali percobaan gagal sebelum penguncian sementara).

---

### BAB III: PENGELOLAAN SURAT MASUK

1. **Merekam Surat Masuk Baru**:
   - Masuk ke menu **Surat Masuk** pada bilah navigasi samping (*sidebar*).
   - Klik tombol **+ Rekam Surat Masuk**.
   - Isi formulir identitas surat:
     - **Nomor Agenda**: Diterbitkan otomatis oleh sistem (`REG-SM/YYYY/MM/####`).
     - **Nomor Surat Dinas**: Masukkan nomor surat resmi dari instansi pengirim (contoh: `088/D/KEMDIKBUD/2026`).
     - **Pengirim**: Nama instansi/pejabat luar pengirim surat.
     - **Perihal / Ringkasan Isi**: Uraian ringkas pokok surat.
     - **Tanggal Surat & Tanggal Diterima**.
     - **Unit Kerja Penerima**: Pilih Rektorat, Biro, atau Fakultas yang dituju.
   - **Unggah Berkas Pindaian**:
     - Klik area unggah berkas, pilih dokumen pindaian asli berformat PDF (maksimal 10 MB).
     - Sistem secara otomatis memvalidasi *Magic Bytes* `%PDF-` untuk memastikan keamanan data.
   - Klik **Simpan Surat Masuk**.

2. **Mendisposisikan Surat**:
   - Pada baris surat masuk yang bersangkutan, klik tombol **Disposisi**.
   - Tentukan **Pejabat/Staf Penerima Disposisi**.
   - Tuliskan **Instruksi Pimpinan** (contoh: *"Tindak lanjuti pembentukan tim ad-hoc."*).
   - Tentukan **Tenggat Waktu Tindak Lanjut**.
   - Klik **Kirim Disposisi**. Status surat secara otomatis beralih menjadi `Sedang Diproses`.

---

### BAB IV: PENGELOLAAN SURAT KELUAR & GENERATOR PENOMORAN

1. **Menerbitkan Nomor Surat Keluar Anti-Race Condition**:
   - Masuk ke menu **Surat Keluar**.
   - Klik tombol **+ Buat Surat Keluar**.
   - Pilih **Unit Kerja Pengirim** dan **Klasifikasi Naskah Dinas** (contoh: `KP.01 - Kepegawaian & Ketenagaan`).
   - Sistem secara deterministik mengeksekusi *pessimistic lock* pada database dan menghasilkan nomor surat urut resmi:
     `0001/UNIV/REK-KP/IX/2026`
   - Lengkapi tujuan surat, perihal, dan draf berkas yang ditandatangani.
   - Klik **Terbitkan Surat Keluar**.

---

### BAB V: ARSIP DIGITAL & PENYIMPANAN AMAN

1. **Menyimpan Dokumen ke Repositori**:
   - Masuk ke menu **Arsip Digital**.
   - Klik **+ Tambah Dokumen Arsip**.
   - Pilih kategori: *Surat Keputusan (SK)*, *Peraturan Rektor*, *Dokumen Akreditasi*, *Perjanjian Kerja Sama (MoU/MoA)*, atau *Sertifikat*.
   - Unggah berkas dokumen PDF pendukung.
   - Berkas disimpan di partisi terisolasi privat (`storage/app/private/`) dengan penamaan berbasis UUID v7.
2. **Melihat & Mengunduh Dokumen**:
   - Klik tombol **Pratinjau / Unduh**.
   - Sistem menghasilkan tautan bertanda tangan aman (*Temporary Signed Route*) yang aktif selama 15 menit.

---

### BAB VI: EKSPOR LAPORAN & PENCADANGAN BASIS DATA

1. **Ekspor Laporan Master Data CSV**:
   - Masuk ke menu **Data Master**.
   - Pada tab *Pegawai* atau *Nomor Surat*, klik tombol **Ekspor CSV**.
   - File CSV terunduh secara instan dengan *header* UTF-8 BOM, dapat langsung dibuka tanpa kendala format di Microsoft Excel.
2. **Pencadangan Data (*Backup*)**:
   - Klik tombol **Pencadangan Sistem** di menu Data Master.
   - Sistem mengompilasi arsip data terstruktur format JSON lengkap dengan *checksum hash* SHA-256 untuk pembuktian integritas data kearsipan.
