<?php

declare(strict_types=1);

namespace App\Actions\Disposisi;

use App\Enums\StatusDisposisi;
use App\Models\Pegawai;
use App\Models\RiwayatDisposisi;
use App\Models\SuratMasuk;
use App\Notifications\DisposisiMasukNotification;
use Illuminate\Support\Facades\DB;

class DelegasikanSuratAction
{
    /**
     * Mendelegasikan naskah dinas ke pejabat berikutnya secara atomik & berjenjang.
     */
    public function execute(
        SuratMasuk $suratMasuk,
        Pegawai $dariPegawai,
        Pegawai $kePegawai,
        string $instruksi,
        ?string $catatanTindakLanjut = null,
        ?string $tenggatWaktu = null
    ): RiwayatDisposisi {
        return DB::transaction(function () use (
            $suratMasuk,
            $dariPegawai,
            $kePegawai,
            $instruksi,
            $catatanTindakLanjut,
            $tenggatWaktu
        ): RiwayatDisposisi {
            // 1. Rekam riwayat disposisi baru
            $riwayat = RiwayatDisposisi::create([
                'surat_masuk_id' => $suratMasuk->id,
                'dari_pegawai_id' => $dariPegawai->id,
                'ke_pegawai_id' => $kePegawai->id,
                'instruksi' => $instruksi,
                'catatan_tindak_lanjut' => $catatanTindakLanjut,
                'tenggat_waktu' => $tenggatWaktu,
                'status' => StatusDisposisi::Diproses,
            ]);

            // 2. Perbarui data naskah surat masuk
            $suratMasuk->update([
                'disposisi_kepada' => $kePegawai->id,
                'instruksi_disposisi' => $instruksi,
                'status_disposisi' => StatusDisposisi::Diproses,
            ]);

            // 3. Picu notifikasi in-app ke pengguna terkait penerima disposisi jika akun aktif
            $userPenerima = $kePegawai->user;
            if ($userPenerima !== null) {
                $userPenerima->notify(new DisposisiMasukNotification(
                    $suratMasuk,
                    $dariPegawai->nama,
                    $instruksi
                ));
            }

            return $riwayat;
        });
    }
}
