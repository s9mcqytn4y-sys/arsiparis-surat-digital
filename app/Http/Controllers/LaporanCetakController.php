<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ArsipDigital;
use App\Models\PengaturanDokumen;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

final class LaporanCetakController extends Controller
{
    public function __invoke(Request $request): View
    {
        $jenis = $request->query('jenis', '');
        $dari = $request->query('dari', Carbon::now()->startOfYear()->format('Y-m-d'));
        $sampai = $request->query('sampai', Carbon::now()->endOfYear()->format('Y-m-d'));

        $user = auth()->user();
        $unitId = $user?->unit_kerja_id;
        $isLeaderOrSuper = $user?->hasRole(['super_admin', 'rektor', 'wakil_rektor']);

        $dtDari = Carbon::parse($dari)->startOfDay();
        $dtSampai = Carbon::parse($sampai)->endOfDay();

        $querySM = SuratMasuk::query()
            ->when(! $isLeaderOrSuper && $unitId, fn ($q) => $q->where('unit_kerja_id', $unitId))
            ->whereBetween('tanggal_surat', [$dtDari, $dtSampai]);

        $querySK = SuratKeluar::query()
            ->when(! $isLeaderOrSuper && $unitId, fn ($q) => $q->where('unit_kerja_id', $unitId))
            ->whereBetween('tanggal_surat', [$dtDari, $dtSampai]);

        $queryAD = ArsipDigital::query()
            ->when(! $isLeaderOrSuper && $unitId, fn ($q) => $q->where('unit_kerja_id', $unitId))
            ->whereBetween('tanggal_dokumen', [$dtDari, $dtSampai]);

        $totalSM = (clone $querySM)->count();
        $totalSK = (clone $querySK)->count();
        $totalAD = (clone $queryAD)->count();

        $tableData = new Collection;

        if ($jenis === '' || $jenis === 'Laporan Surat Masuk' || $jenis === 'Rekapitulasi Bulanan' || $jenis === 'Statistik Tahunan') {
            foreach ((clone $querySM)->latest('tanggal_surat')->get() as $item) {
                $tableData->push([
                    'tanggal' => $item->tanggal_surat ? $item->tanggal_surat->format('d/m/Y') : '-',
                    'raw_date' => $item->tanggal_surat,
                    'nomor_surat' => $item->nomor_surat,
                    'perihal' => $item->perihal,
                    'status' => 'SURAT MASUK',
                ]);
            }
        }

        if ($jenis === '' || $jenis === 'Laporan Surat Keluar' || $jenis === 'Rekapitulasi Bulanan' || $jenis === 'Statistik Tahunan') {
            foreach ((clone $querySK)->latest('tanggal_surat')->get() as $item) {
                $tableData->push([
                    'tanggal' => $item->tanggal_surat ? $item->tanggal_surat->format('d/m/Y') : '-',
                    'raw_date' => $item->tanggal_surat,
                    'nomor_surat' => $item->nomor_surat,
                    'perihal' => $item->perihal,
                    'status' => 'SURAT KELUAR',
                ]);
            }
        }

        if ($jenis === '' || $jenis === 'Laporan Arsip Digital' || $jenis === 'Rekapitulasi Bulanan' || $jenis === 'Statistik Tahunan') {
            foreach ((clone $queryAD)->latest('tanggal_dokumen')->get() as $item) {
                $tableData->push([
                    'tanggal' => $item->tanggal_dokumen ? $item->tanggal_dokumen->format('d/m/Y') : '-',
                    'raw_date' => $item->tanggal_dokumen,
                    'nomor_surat' => $item->nomor_dokumen ?: '-',
                    'perihal' => $item->judul,
                    'status' => 'ARSIP DIGITAL',
                ]);
            }
        }

        $rekapitulasi = $tableData->sortByDesc('raw_date')->values();
        $pengaturan = PengaturanDokumen::getAktif();

        return view('laporan.cetak', [
            'pengaturan' => $pengaturan,
            'jenis' => $jenis,
            'dari' => Carbon::parse($dari)->format('d/m/Y'),
            'sampai' => Carbon::parse($sampai)->format('d/m/Y'),
            'totalSM' => $totalSM,
            'totalSK' => $totalSK,
            'totalAD' => $totalAD,
            'rekapitulasi' => $rekapitulasi,
        ]);
    }
}
