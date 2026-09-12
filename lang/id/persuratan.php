<?php

declare(strict_types=1);

return [
    'app_name' => 'Arsiparis Surat Digital',
    'app_institution' => 'Tata Usaha & Kearsipan Perguruan Tinggi',

    // Autentikasi
    'auth' => [
        'login_title' => 'Portal Tata Usaha & Kearsipan',
        'login_subtitle' => 'Autentikasi Surat Digital Terpusat',
        'nip_or_email' => 'Username, Surel, atau NIP Resmi',
        'password' => 'Kata Sandi',
        'remember_me' => 'Ingat sesi saya',
        'submit_login' => 'Masuk',
        'logout' => 'Keluar dari Sistem',
        'failed' => 'Identitas atau kata sandi yang dimasukkan tidak valid.',
        'login_failed' => 'Identitas atau kata sandi yang dimasukkan tidak valid.',
        'login_success' => 'Berhasil masuk ke portal persuratan digital.',
        'logout_success' => 'Sesi kedinasan telah berhasil diakhiri.',
        'throttle' => 'Terlalu banyak percobaan masuk. Silakan coba kembali dalam :seconds detik.',
        'protected_notice' => 'Sistem Kearsipan Terlindung Protokol Zero Trust. Seluruh akses surat terekam dalam audit trail.',
    ],

    // Navigasi & Modul
    'nav' => [
        'dashboard' => 'Dashboard Tata Usaha',
        'surat_masuk' => 'Surat Masuk',
        'surat_keluar' => 'Surat Keluar',
        'arsip_digital' => 'Arsip Digital',
        'master_data' => 'Data Master',
        'laporan' => 'Laporan & Buku Agenda',
        'profil' => 'Profil Pengguna',
    ],

    // Surat Masuk
    'surat_masuk' => [
        'title' => 'Registrasi & Pengelolaan Surat Masuk',
        'nomor_agenda' => 'Nomor Agenda',
        'nomor_surat' => 'Nomor Surat Asal',
        'pengirim' => 'Instansi / Pihak Pengirim',
        'tanggal_surat' => 'Tanggal Surat',
        'tanggal_terima' => 'Tanggal Diterima TU',
        'perihal' => 'Perihal Surat',
        'ringkasan' => 'Ringkasan Isi Surat',
        'berkas' => 'Berkas Pindaian (Format PDF Resmi)',
        'tindakan' => 'Aksi & Tindak Lanjut',
        'unit_penerima' => 'Unit Kerja Penerima',
    ],

    // Surat Keluar
    'surat_keluar' => [
        'title' => 'Penerbitan & Penomoran Surat Keluar',
        'nomor_surat' => 'Nomor Surat Resmi',
        'kode_klasifikasi' => 'Kode Klasifikasi Surat',
        'tujuan' => 'Tujuan Surat / Penerima',
        'jenis_surat' => 'Jenis Surat Dinas',
        'tanggal_surat' => 'Tanggal Surat Keluar',
        'terbitkan_nomor' => 'Kunci & Terbitkan Nomor Resmi',
        'cetak_lembar' => 'Cetak Lembar Pengantar',
    ],

    // Cetak & Keamanan
    'cetak' => [
        'buku_agenda' => 'BUKU AGENDA PERSURATAN RESMI',
        'rahasia' => 'RAHASIA',
        'penting' => 'PENTING',
        'biasa' => 'BIASA',
        'paraf' => 'Tanda Tangan / Paraf Pejabat',
        'tanggal_cetak' => 'Tanggal Cetak Dokumen',
    ],
];
