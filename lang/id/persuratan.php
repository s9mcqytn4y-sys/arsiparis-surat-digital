<?php

declare(strict_types=1);

return [
    'app_name' => 'Arsiparis Surat Digital',
    'app_institution' => 'Tata Usaha & Kearsipan Perguruan Tinggi',

    // Autentikasi
    'auth' => [
        'login_title' => 'Portal Naskah Dinas Terpusat',
        'login_subtitle' => 'Silakan masuk menggunakan identitas resmi universitas',
        'nip_or_email' => 'NIP / NIDN / Alamat Surel Resmi',
        'password' => 'Kata Sandi',
        'remember_me' => 'Ingat sesi di peramban ini',
        'submit_login' => 'Masuk ke Portal Naskah Dinas',
        'logout' => 'Keluar dari Sistem',
        'failed' => 'Identitas atau kata sandi yang dimasukkan tidak cocok dengan data universitas.',
        'throttle' => 'Terlalu banyak percobaan login. Silakan coba kembali dalam :seconds detik.',
        'protected_notice' => 'Sistem Kearsipan Terlindung Protokol Zero Trust. Setiap aktivitas terekam dalam log audit.',
    ],

    // Navigasi & Modul
    'nav' => [
        'dashboard' => 'Beranda Tata Usaha',
        'surat_masuk' => 'Naskah Dinas Masuk',
        'surat_keluar' => 'Naskah Dinas Keluar',
        'disposisi' => 'Pelacakan Disposisi',
        'arsip_digital' => 'Repositori Berkas Arsip',
        'master_data' => 'Master Klasifikasi & Pola',
        'profil' => 'Profil Pengguna',
    ],

    // Naskah Dinas Masuk
    'surat_masuk' => [
        'title' => 'Registrasi & Pengelolaan Surat Masuk',
        'nomor_agenda' => 'Nomor Agenda',
        'nomor_surat' => 'Nomor Surat Asal',
        'pengirim' => 'Instansi / Pihak Pengirim',
        'tanggal_surat' => 'Tanggal Naskah',
        'tanggal_terima' => 'Tanggal Diterima TU',
        'perihal' => 'Perihal Naskah',
        'ringkasan' => 'Ringkasan Isi Surat',
        'berkas' => 'Berkas Pindaian (Format PDF Resmi)',
        'status_disposisi' => 'Status Disposisi',
        'tindakan' => 'Aksi & Tindak Lanjut',
        'disposisi_ke' => 'Disposisikan Kepada Pejabat',
        'instruksi' => 'Instruksi / Catatan Pimpinan',
    ],

    // Naskah Dinas Keluar
    'surat_keluar' => [
        'title' => 'Penerbitan & Penomoran Surat Keluar',
        'nomor_surat' => 'Nomor Surat Resmi',
        'kode_klasifikasi' => 'Kode Klasifikasi Naskah',
        'tujuan' => 'Tujuan Surat / Penerima',
        'penandatangan' => 'Pejabat Penandatangan',
        'tanggal_surat' => 'Tanggal Surat Keluar',
        'terbitkan_nomor' => 'Kunci & Terbitkan Nomor Resmi',
        'cetak_lembar' => 'Cetak Lembar Pengantar',
    ],

    // Cetak & Keamanan
    'cetak' => [
        'lembar_disposisi' => 'LEMBAR DISPOSISI NASKAH DINAS',
        'rahasia' => 'RAHASIA',
        'penting' => 'PENTING',
        'biasa' => 'BIASA',
        'paraf' => 'Tanda Tangan / Paraf Pejabat',
        'tanggal_penyelesaian' => 'Batas Waktu Penyelesaian',
    ],
];
