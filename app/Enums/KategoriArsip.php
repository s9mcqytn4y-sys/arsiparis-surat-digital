<?php

declare(strict_types=1);

namespace App\Enums;

enum KategoriArsip: string
{
    case SK_REKTOR = 'sk_rektor';
    case SK_DEKAN = 'sk_dekan';
    case AKREDITASI = 'akreditasi';
    case KURIKULUM = 'kurikulum';
    case KERJASAMA = 'kerjasama';
    case KEPEGAWAIAN = 'kepegawaian';
    case KEUANGAN = 'keuangan';
    case LAINNYA = 'lainnya';

    public function label(): string
    {
        return match ($this) {
            self::SK_REKTOR => 'Surat Keputusan Rektor',
            self::SK_DEKAN => 'Surat Keputusan Dekan',
            self::AKREDITASI => 'Dokumen Akreditasi (BAN-PT / LAM)',
            self::KURIKULUM => 'Kurikulum & Silabus Program Studi',
            self::KERJASAMA => 'Perjanjian Kerjasama (MoU / MoA)',
            self::KEPEGAWAIAN => 'Arsip Kepegawaian & Dosen',
            self::KEUANGAN => 'Laporan & Dokumen Keuangan',
            self::LAINNYA => 'Arsip Dokumen Lainnya',
        };
    }
}
