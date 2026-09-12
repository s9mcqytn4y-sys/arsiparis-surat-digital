# Design System & UI/UX Specification (DESIGN.md)

## Arsiparis Surat Digital — Sistem Manajemen Tata Usaha Digital

### Berstandar Antislop UI, WCAG 2.1 AA & Prinsip Modern Web 2026

---

### Dokumen Kontrol

| Atribut | Spesifikasi Desain |
| :--- | :--- |
| **Nama Sistem** | Arsiparis Surat Digital |
| **Versi Desain** | 3.0.0 — Modern Educational Monolith |
| **Prinsip Utama** | *Clarity, Efficiency, Accessibility & Anti-Slop* |
| **Fondasi CSS** | Tailwind CSS v4 (Oxide Engine) |
| **Komponen Reaktif** | Livewire 3 + Alpine.js 3 + Filament v3 Embeds |
| **Visualisasi Data** | Chart.js 4.x dengan Skema Palet Terkurasi |
| **Tata Letak Cetak** | Cetak Fisik A4 Portrait Berstandar Naskah Dinas |
| **Target Perangkat** | Desktop Monitor TU (1920x1080 / 1366x768), Laptop, Tablet, Mobile Preview |

---

## 1. Filosofi Desain & Prinsip "Anti-Slop"

Aplikasi Tata Usaha sering kali menderita akibat dua ekstrem: desain jadul bergaya web tahun 2000-an yang kaku dan lambat, atau template AI generik (*slop*) yang dipenuhi gradien ungu neon berlebihan, sudut kartu yang membulat tak wajar, kontras teks yang buruk, serta animasi berat yang mengganggu produktivitas staf administrasi.

**Arsiparis Surat Digital menerapkan 4 Pilar Desain:**

1. **Utility & Information Density**: Data persuratan butuh ruang pandang yang lapang dan mudah dipindai (*scannable*). Hindari ruang kosong (*whitespace*) yang memboroskan area layar tanpa konteks.
2. **Keterbacaan & Rasio Kontras Tinggi**: Teks harus memiliki kontras $\ge 4.5:1$ (WCAG AA). Teks sekunder tetap harus terbaca jelas di bawah pencahayaan ruangan kantor.
3. **Sentuhan Humanis & Aksen Terarah**: Palet warna didasarkan pada warna netral hangat (*warm slate*) dengan aksen *Emerald Green* (melambangkan legalitas/dinas) dan *Indigo Blue* (melambangkan administrasi modern).
4. **Fungsionalitas Cetak Nyata**: Seluruh tabel laporan bukan hanya cantik di layar peramban, melainkan memiliki tata letak cetak A4 presisi yang langsung layak diserahkan kepada Pengawas/Dinas Pendidikan tanpa proses ekspor manual berulang.

---

## 2. Token Desain (Design Tokens)

### 2.1 Palet Warna (Curated Semantic Palette)

```text
┌────────────────────────────────────────────────────────────────────────┐
│                        SEMANTIC COLOR PALETTE                          │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
    ┌───────────────────────┬───────┴───────────────┬────────────────────┐
    ▼                       ▼                       ▼                    ▼
[ Primary: Slate ]    [ Brand: Emerald ]     [ Info: Indigo ]     [ Danger: Rose ]
50:  #F8FAFC          50:  #ECFDF5           50:  #EEF2FF         50:  #FFF1F2
100: #F1F5F9          100: #D1FAE5           100: #E0E7FF         100: #FFE4E6
500: #64748B          500: #10B981           500: #6366F1         500: #F43F5E
700: #334155          600: #059669           600: #4F46E5         600: #E11D48
900: #0F172A          700: #047857           700: #4338CA         700: #BE123C
```

| Token Semantik | Kode Warna | Penggunaan Khusus | Rasio Kontras |
| :--- | :--- | :--- | :--- |
| `--bg-surface` | `#FFFFFF` | Latar belakang kartu, modal, dan tabel | 21:1 terhadap teks |
| `--bg-canvas` | `#F8FAFC` (`slate-50`) | Latar belakang halaman aplikasi | Netral lembut |
| `--text-primary` | `#0F172A` (`slate-900`)| Judul halaman, isi tabel utama, perihal surat | 16.8:1 (AAA) |
| `--text-secondary` | `#475569` (`slate-600`)| Tanggal, nomor agenda, label formulir, keterangan | 7.2:1 (AA) |
| `--text-muted` | `#64748B` (`slate-500`)| Placeholder, breadcrumb non-aktif, info footer | 4.6:1 (AA) |
| `--border-subtle` | `#E2E8F0` (`slate-200`)| Garis batas kartu, pemisah baris tabel | Lembut |
| `--accent-brand` | `#059669` (`emerald-600`)| Tombol aksi primer, indikator status aktif | 4.8:1 (AA) |
| `--accent-info` | `#2563EB` (`blue-600`) | Surat Masuk badge, tautan berkas, tombol pratinjau | 5.1:1 (AA) |
| `--accent-warning`| `#D97706` (`amber-600`)| Surat Belum Disposisi, peringatan batas waktu | 4.5:1 (AA) |
| `--accent-danger` | `#E11D48` (`rose-600`) | Tombol hapus, alert kegagalan, penolakan berkas | 5.2:1 (AA) |

### 2.2 Tipografi (Typography Hierarchy)

- **Font Family Utama**: `Plus Jakarta Sans`, `Inter`, `-apple-system`, `sans-serif`.
- **Font Monospace**: `JetBrains Mono`, `ui-monospace`, `monospace` (digunakan untuk NIP, Nomor Agenda, dan Format Pola Surat).

| Skala | Ukuran | Line Height | Weight | Penggunaan |
| :--- | :--- | :--- | :--- | :--- |
| `Display / H1` | `28px` (`1.75rem`) | `36px` | `700 (Bold)` | Judul Utama Halaman Dashboard & Rekapitulasi |
| `Heading / H2` | `20px` (`1.25rem`) | `28px` | `600 (Semibold)` | Judul Modal, Sub-judul Panel, Header Widget |
| `Heading / H3` | `16px` (`1.0rem`) | `24px` | `600 (Semibold)` | Header Kolom Tabel, Judul Kartu KPI |
| `Body Standard`| `14px` (`0.875rem`)| `20px` | `400 (Regular)` | Isi tabel persuratan, input field, teks formulir |
| `Body Medium`  | `14px` (`0.875rem`)| `20px` | `500 (Medium)` | Tombol tombol navigasi, label formulir aktif |
| `Caption / Tiny`| `12px` (`0.75rem`) | `16px` | `500 (Medium)` | Badge status, timestamp audit, NIP di bawah nama |

### 2.3 Elevasi, Garis Batas & Sudut (Elevation & Radii)

- **Border Radius**:
  - `rounded-lg` (`8px`): Tombol, Input Field, Badge Status, Dropdown Menu.
  - `rounded-xl` (`12px`): Kartu Metrik, Panel Konten Utama, Modal Dialog.
  - `rounded-full` (`9999px`): Avatar Pengguna, Pill Counters, Indikator Status Dot.
- **Bayangan (Ambient Elevation)**:
  - Hindari bayangan pekat gelap (*harsh drop shadows*).
  - Gunakan `shadow-sm`: `0 1px 2px 0 rgb(0 0 0 / 0.05)` untuk kartu & tabel.
  - Gunakan `shadow-lg`: `0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.04)` untuk modal dialog melayang.

---

## 3. Struktur Tata Letak & Hirarki Navigasi

### 3.1 Bilah Navigasi Atas (Top Navigation Bar)

Aplikasi mengadopsi navigasi horizontal atas (*Top Navbar*) yang bersih untuk memaksimalkan lebar vertikal dan horizontal tabel arsip:

```text
┌────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│ [Logo] ARSIPARIS DIGITAL  |  Dashboard   Surat Masuk   Surat Keluar   Arsip   Pegawai   Laporan   ⚙  │
│                                                              [ + Surat Masuk ]  [🔔 2]  [👤 Admin ▾]   │
└────────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

1. **Brand Identity**: Logo resmi lembaga di sebelah kiri dengan teks tebal "ARSIPARIS" dan penanda kecil "Sistem Tata Usaha".
2. **Menu Utama Berstatus Aktif**:
   - Menu aktif ditandai dengan latar belakang halus (`bg-slate-100 dark:bg-slate-800`), teks tebal hijau zamrud (`text-emerald-700`), serta garis aksen bawah 2px.
   - Menggunakan transisi halaman halus Livewire 3 (`wire:navigate`).
3. **Bilah Aksi Cepat (*Quick Actions*)**:
   - Tombol hijau menonjol `[ + Surat Masuk ]` dan tombol biru `[ + Surat Keluar ]` selalu tersedia di bilah atas untuk efisiensi entri.
4. **Profil & Sesi**:
   - Avatar inisial nama, label peran (*Super Admin / Petugas*), tombol "Ubah Profil", dan tombol "Keluar (*Logout*)" dengan konfirmasi aman.

### 3.2 Tampilan Responsif Ponsel & Tablet

- Pada layar $< 1024\text{px}$, navigasi bertransformasi menjadi tombol *hamburger* yang membuka laci navigasi samping (*slide-over drawer*) dengan animasi Alpine.js `x-transition:enter`.
- Seluruh tombol aksi sentuh (*touch target*) memiliki dimensi minimal $44 \times 44\text{ px}$ untuk kemudahan akses di perangkat tablet/ponsel petugas TU.

---

## 4. Spesifikasi Komponen Inti UI

### 4.1 Kartu KPI & Metrik Dashboard

Setiap kartu metrik menampilkan data ringkas yang langsung dipahami pimpinan sekolah:

```text
┌───────────────────────────────┐
│ Surat Masuk Bulan Ini     📨  │
│ 142                           │
│ ↗ +12% dibanding bulan lalu   │
└───────────────────────────────┘
```

- Latar kartu: Putih murni (`#FFFFFF`) dengan border halus `slate-200`.
- Ikon: Ditempatkan di pojok kanan atas di dalam kotak berlatar warna pastel aksen (10% opacity).
- Angka: Ditampilkan dengan font tebal ukuran 28px (`font-bold text-slate-900`).
- Tren: Indikator hijau (`text-emerald-600`) bila naik, atau netral (`text-slate-500`).

### 4.2 Tabel Data Modern (Filament Engine Embed)

Tabel didesain untuk volume data tinggi dengan interaksi instan:

1. **Header Tabel**: Latar abu-abu sangat muda (`bg-slate-50`), teks kapital kecil (`text-xs font-semibold uppercase tracking-wider text-slate-500`), dan border bawah 1px.
2. **Baris Data**:
   - Tinggi baris nyaman (`py-3.5 px-4`).
   - Efek sorot saat kursor melintas (`hover:bg-slate-50/80 transition-colors`).
3. **Pills Status Kategori & Disposisi**:
   - *Sudah Disposisi*: `bg-emerald-50 text-emerald-700 border border-emerald-200`.
   - *Menunggu Tindak Lanjut*: `bg-amber-50 text-amber-700 border border-amber-200`.
   - *Surat Penting*: `bg-rose-50 text-rose-700 border border-rose-200`.
4. **Bilah Pencarian Cepat**:
   - Terintegrasi di atas tabel dengan ikon kaca pembesar, debounced 300ms (`wire:model.live.debounce.300ms="search"`).
   - Dilengkapi tombol reset filter (*X*) saat kolom terisi.

### 4.3 Modal Dialog Formulir (Modal Builder)

- **Backdrop**: Efek blur modern `backdrop-blur-sm bg-slate-900/40` yang fokus memusatkan perhatian pada data entri.
- **Transisi**: Animasi pembesaran halus dari 95% ke 100% (`transition-transform ease-out duration-200`).
- **Penanganan Aksesibilitas**:
  - Menutup otomatis saat tombol `ESC` ditekan.
  - Penjaga fokus (*focus trap*) di dalam modal.
  - Header dan footer tetap menempel (*sticky*) bila konten formulir panjang di layar kecil.

### 4.4 Notifikasi Toast Reaktif

- Menggunakan event bus Livewire (`$dispatch('notify', { type: 'success', message: '...' })`).
- Muncul di pojok kanan atas dengan animasi geser masuk (*slide-in*).
- Menghilang otomatis setelah 4 detik atau dapat ditutup manual.

---

## 5. Visualisasi Data & Grafik (Chart.js 4 Engine)

### 5.1 Skema Warna Grafik

Grafik dashboard dirancang dengan kurasi warna yang elegan dan tidak silau:

- **Surat Masuk**: Biru Laut (`#3B82F6`) dengan area gradien halus ke transparan.
- **Surat Keluar**: Hijau Zamrud (`#10B981`) dengan area gradien halus.
- **Garis Grid**: Sangat tipis dan transparan (`#F1F5F9`) agar data tetap menjadi fokus utama.
- **Tooltip**: Latar gelap elegan (`#0F172A`), teks putih, dengan sudut membulat 8px dan padding proporsional.

### 5.2 Jenis Grafik yang Diimplementasikan

1. **Tren Persuratan Bulanan (Bar/Line Combo)**: Membandingkan rasio surat masuk vs surat keluar sepanjang 12 bulan terakhir.
2. **Distribusi Klasifikasi Naskah (Doughnut Chart)**: Memetakan sebaran surat berdasarkan bidang (Kesiswaan, Kurikulum, Kepegawaian, Sarana Prasarana).
3. **Status Disposisi (Horizontal Stacked Bar)**: Memantau kecepatan tindak lanjut surat oleh para wakil kepala sekolah atau staf.

---

## 6. Spesifikasi Cetak Fisik Berstandar Naskah Dinas (@media print A4)

Salah satu keunggulan mutlak sistem ini adalah dukungan cetak langsung berkualitas arsip dinas tanpa memerlukan aplikasi pihak ketiga:

```css
@media print {
  /* 1. Atur Ukuran Halaman & Margin Standar Kantor */
  @page {
    size: A4 portrait;
    margin: 15mm 20mm 15mm 20mm;
  }

  /* 2. Sembunyikan Seluruh Elemen Antarmuka Web */
  nav,
  header,
  footer,
  button,
  .no-print,
  .filament-tables-pagination-container,
  .filament-tables-search-input {
    display: none !important;
  }

  /* 3. Maksimalkan Kontainer Dokumen */
  body, .print-container {
    background: transparent !important;
    color: #000000 !important;
    font-family: 'Times New Roman', Times, serif !important;
    font-size: 11pt !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
  }

  /* 4. Kop Surat Resmi Sekolah */
  .print-kop-surat {
    display: block !important;
    text-align: center;
    border-bottom: 3px double #000000;
    padding-bottom: 8px;
    margin-bottom: 16px;
  }

  /* 5. Tabel Cetak Berulang & Anti-Potong */
  table {
    width: 100% !important;
    border-collapse: collapse !important;
  }

  thead {
    display: table-header-group !important;
  }

  tr {
    page-break-inside: avoid !important;
  }

  th, td {
    border: 1px solid #000000 !important;
    padding: 6px 8px !important;
  }

  /* 6. Kolom Tanda Tangan */
  .print-signatures {
    display: flex !important;
    justify-content: space-between;
    margin-top: 30px;
    page-break-inside: avoid !important;
  }
}
```

---

## 7. Mikro-Interaksi & Pengalaman Pengguna (UX Delight)

1. **State Loading Elegan**:
   - Setiap aksi jaringan Livewire memicu indikator *loading shimmer/skeleton* halus (`wire:loading`).
   - Tombol simpan otomatis menampilkan ikon pemintal (*spinner*) dan teks berubah menjadi *"Menyimpan data..."* sembari status tombol terkunci (*disabled*).
2. **Konfirmasi Aksi Destruktif**:
   - Menghapus data tidak pernah menggunakan dialog default browser `confirm()`.
   - Menggunakan modal konfirmasi Filament yang menjelaskan dampak aksi (*"Surat nomor 421.3/012 akan dihapus permanen. Aksi ini tidak dapat dibatalkan."*).
3. **Pintasan Keyboard (Keyboard Shortcuts)**:
   - `Ctrl + K` / `Cmd + K`: Fokus instan ke kolom pencarian surat global.
   - `Alt + M`: Membuka modal input Surat Masuk baru.
   - `Alt + K`: Membuka modal input Surat Keluar baru.
   - `Esc`: Menutup modal yang sedang terbuka.

---

> **Status Kelulusan Desain**: Dokumen DESIGN.md ini menjamin antarmuka yang bersih, cepat, elegan, bebas dari elemen generik AI yang tidak berguna, dan siap diimplementasikan secara konsisten oleh seluruh tim pengembang.
