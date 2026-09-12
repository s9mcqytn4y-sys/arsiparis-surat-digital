<?php

declare(strict_types=1);

return [
    'app_name' => 'Arsiparis Surat Digital',
    'app_institution' => 'University Digital Governance & Official Archives',

    // Authentication
    'auth' => [
        'login_title' => 'Secretariat & Archive Portal',
        'login_subtitle' => 'Centralized University Official Mail Authentication',
        'nip_or_email' => 'Username, Official Email, or Employee ID (NIP)',
        'password' => 'Password',
        'remember_me' => 'Remember my session',
        'submit_login' => 'Sign In',
        'logout' => 'Sign Out',
        'failed' => 'The provided credentials do not match our official records.',
        'login_failed' => 'The provided credentials do not match our official records.',
        'login_success' => 'Successfully authenticated to digital records portal.',
        'logout_success' => 'Official session has been safely terminated.',
        'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
        'protected_notice' => 'Protected under Zero-Trust University Protocol. All correspondence activities are tracked in the immutable audit trail.',
    ],

    // Navigation & Modules
    'nav' => [
        'dashboard' => 'Secretariat Dashboard',
        'surat_masuk' => 'Incoming Letters',
        'surat_keluar' => 'Outgoing Letters',
        'arsip_digital' => 'Digital Archives',
        'master_data' => 'Master Data',
        'laporan' => 'Reports & Agenda Book',
        'profil' => 'User Profile',
    ],

    // Incoming Letters
    'surat_masuk' => [
        'title' => 'Registration & Incoming Correspondence Management',
        'nomor_agenda' => 'Agenda Number',
        'nomor_surat' => 'Origin Letter Number',
        'pengirim' => 'Sender Institution / Agency',
        'tanggal_surat' => 'Letter Date',
        'tanggal_terima' => 'Date Received by TU',
        'perihal' => 'Subject / Regard',
        'ringkasan' => 'Executive Summary',
        'berkas' => 'Official PDF Scanned File',
        'tindakan' => 'Actions & Follow-up',
        'unit_penerima' => 'Recipient Work Unit',
    ],

    // Outgoing Letters
    'surat_keluar' => [
        'title' => 'Official Outgoing Letter Issuance & Numbering',
        'nomor_surat' => 'Official Letter Number',
        'kode_klasifikasi' => 'Letter Classification Code',
        'tujuan' => 'Recipient Institution / Unit',
        'jenis_surat' => 'Official Letter Type',
        'tanggal_surat' => 'Outgoing Letter Date',
        'terbitkan_nomor' => 'Lock & Issue Official Number',
        'cetak_lembar' => 'Print Transmittal Slip',
    ],

    // Printing & Security
    'cetak' => [
        'buku_agenda' => 'OFFICIAL CORRESPONDENCE AGENDA BOOK',
        'rahasia' => 'SECRET',
        'penting' => 'CONFIDENTIAL / IMPORTANT',
        'biasa' => 'STANDARD',
        'paraf' => 'Official Signature / Approval',
        'tanggal_cetak' => 'Document Print Date',
    ],
];
