<?php

declare(strict_types=1);

namespace App\Constants;

final class AppConstants
{
    // Nama Aplikasi & Institusi
    public const string APP_NAME = 'Arsiparis Surat Digital';

    public const string INSTITUSI_DEFAULT = 'Universitas Digital Nusantara';

    public const string FAKULTAS_DEFAULT = 'Fakultas Teknologi Informasi dan Komunikasi';

    // Judul & Subjudul Halaman
    public const string TITLE_DASHBOARD = 'Dashboard Tata Usaha';

    public const string SUBTITLE_DASHBOARD = 'Portal registrasi harian, pencatatan surat masuk/keluar, dan repositori kearsipan universitas.';

    public const string TITLE_SURAT_MASUK = 'Pencatatan Surat Masuk';

    public const string SUBTITLE_SURAT_MASUK = 'Registrasi, verifikasi, dan pelacakan surat masuk di lingkungan perguruan tinggi.';

    public const string TITLE_SURAT_KELUAR = 'Penerbitan Surat Keluar';

    public const string SUBTITLE_SURAT_KELUAR = 'Penomoran resmi otomatis, registrasi agenda, dan pengarsipan surat keluar.';

    public const string TITLE_ARSIP_DIGITAL = 'Repositori Arsip Digital';

    public const string SUBTITLE_ARSIP_DIGITAL = 'Penyimpanan dokumen institusi, sertifikat akreditasi, dan SK resmi perguruan tinggi.';

    public const string TITLE_LAPORAN = 'Laporan & Analitik Persuratan';

    public const string SUBTITLE_LAPORAN = 'Visualisasi rekapitulasi data dokumen, statistik persuratan, dan pratinjau cetak resmi.';

    public const string TITLE_PENGATURAN_DOKUMEN = 'Pengaturan Kop Surat & Templat Dokumen';

    public const string SUBTITLE_PENGATURAN_DOKUMEN = 'Konfigurasi identitas perguruan tinggi, logo resmi, dan alamat untuk cetakan surat & laporan kearsipan.';

    public const string TITLE_DATA_MASTER = 'Data Master System & Kepegawaian';

    public const string SUBTITLE_DATA_MASTER = 'Pengelolaan data pegawai, penomoran surat, dan hak akses administrator persuratan.';

    // Pesan Notifikasi Flash
    public const string MSG_SIMPAN_SUKSES = 'Data berhasil disimpan ke sistem.';

    public const string MSG_UPDATE_SUKSES = 'Perubahan data berhasil diperbarui.';

    public const string MSG_HAPUS_SUKSES = 'Data berhasil dihapus dari sistem.';

    public const string MSG_FILE_TIDAK_ADA = 'Berkas fisik dokumen tidak ditemukan pada repositori terproteksi.';
}
