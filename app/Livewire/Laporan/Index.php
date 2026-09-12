<?php

declare(strict_types=1);

namespace App\Livewire\Laporan;

use App\Models\ArsipDigital;
use App\Models\PengaturanDokumen;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url as UrlParam;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[UrlParam(as: 'jenis')]
    public string $jenisLaporan = '';

    #[UrlParam(as: 'dari')]
    public string $periodeDari = '';

    #[UrlParam(as: 'sampai')]
    public string $periodeSampai = '';

    public string $searchTable = '';

    public int $perPage = 10;

    // Active calculated filter state
    public string $appliedJenis = '';

    public string $appliedDari = '';

    public string $appliedSampai = '';

    // Calculated metrics
    public int $totalSuratMasuk = 0;

    public int $totalSuratKeluar = 0;

    public int $totalArsip = 0;

    public int $totalDokumen = 0;

    /** @var array<int, int> */
    public array $chartMasukBulanan = [];

    /** @var array<int, int> */
    public array $chartKeluarBulanan = [];

    public function mount(): void
    {
        $this->periodeDari = Carbon::now()->startOfYear()->format('Y-m-d');
        $this->periodeSampai = Carbon::now()->endOfYear()->format('Y-m-d');

        $this->tampilkanLaporan();
    }

    public function updatingSearchTable(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->jenisLaporan = '';
        $this->periodeDari = Carbon::now()->startOfYear()->format('Y-m-d');
        $this->periodeSampai = Carbon::now()->endOfYear()->format('Y-m-d');
        $this->searchTable = '';
        $this->resetPage();

        $this->tampilkanLaporan();
    }

    public function tampilkanLaporan(): void
    {
        $this->appliedJenis = $this->jenisLaporan;
        $this->appliedDari = $this->periodeDari ?: Carbon::now()->startOfYear()->format('Y-m-d');
        $this->appliedSampai = $this->periodeSampai ?: Carbon::now()->endOfYear()->format('Y-m-d');

        $user = auth()->user();
        $unitId = $user?->unit_kerja_id;
        $isLeaderOrSuper = $user?->hasRole(['super_admin', 'rektor', 'wakil_rektor']);

        $dtDari = Carbon::parse($this->appliedDari)->startOfDay();
        $dtSampai = Carbon::parse($this->appliedSampai)->endOfDay();

        // 1. Query Surat Masuk
        $querySM = SuratMasuk::query()
            ->when(! $isLeaderOrSuper && $unitId, fn ($q) => $q->where('unit_kerja_id', $unitId))
            ->whereBetween('tanggal_surat', [$dtDari, $dtSampai]);

        // 2. Query Surat Keluar
        $querySK = SuratKeluar::query()
            ->when(! $isLeaderOrSuper && $unitId, fn ($q) => $q->where('unit_kerja_id', $unitId))
            ->whereBetween('tanggal_surat', [$dtDari, $dtSampai]);

        // 3. Query Arsip Digital
        $queryAD = ArsipDigital::query()
            ->when(! $isLeaderOrSuper && $unitId, fn ($q) => $q->where('unit_kerja_id', $unitId))
            ->whereBetween('tanggal_dokumen', [$dtDari, $dtSampai]);

        $this->totalSuratMasuk = (clone $querySM)->count();
        $this->totalSuratKeluar = (clone $querySK)->count();
        $this->totalArsip = (clone $queryAD)->count();
        $this->totalDokumen = $this->totalSuratMasuk + $this->totalSuratKeluar + $this->totalArsip;

        // 4. Hitung Tren Bulanan ChartJS (Jan - Des)
        $this->chartMasukBulanan = array_fill(1, 12, 0);
        $this->chartKeluarBulanan = array_fill(1, 12, 0);

        $listSM = (clone $querySM)->get(['tanggal_surat']);
        foreach ($listSM as $sm) {
            if ($sm->tanggal_surat) {
                $m = (int) $sm->tanggal_surat->format('n');
                $this->chartMasukBulanan[$m] = ($this->chartMasukBulanan[$m] ?? 0) + 1;
            }
        }

        $listSK = (clone $querySK)->get(['tanggal_surat']);
        foreach ($listSK as $sk) {
            if ($sk->tanggal_surat) {
                $m = (int) $sk->tanggal_surat->format('n');
                $this->chartKeluarBulanan[$m] = ($this->chartKeluarBulanan[$m] ?? 0) + 1;
            }
        }

        $this->dispatch('update-chart-data',
            masuk: array_values($this->chartMasukBulanan),
            keluar: array_values($this->chartKeluarBulanan)
        );
    }

    public function exportExcel(): StreamedResponse
    {
        $filename = 'Laporan_Kearsipan_'.date('Ymd_His').'.csv';

        $rekapData = $this->getRekapitulasiData();

        return response()->streamDownload(function () use ($rekapData): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            // UTF-8 BOM untuk MS Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['LAPORAN REKAPITULASI DOKUMEN & SURAT DINAS']);
            fputcsv($handle, ['Periode', $this->appliedDari.' s/d '.$this->appliedSampai]);
            fputcsv($handle, ['Jenis Laporan', $this->appliedJenis ?: 'Semua Jenis Laporan']);
            fputcsv($handle, []);
            fputcsv($handle, ['No', 'Tanggal', 'Nomor Surat / Dokumen', 'Perihal / Judul', 'Jenis Surat']);

            foreach ($rekapData as $idx => $row) {
                fputcsv($handle, [
                    $idx + 1,
                    $row['tanggal'],
                    $row['nomor_surat'],
                    $row['perihal'],
                    $row['status'],
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function getRekapitulasiData(): Collection
    {
        $user = auth()->user();
        $unitId = $user?->unit_kerja_id;
        $isLeaderOrSuper = $user?->hasRole(['super_admin', 'rektor', 'wakil_rektor']);

        $dtDari = Carbon::parse($this->appliedDari ?: Carbon::now()->startOfYear()->format('Y-m-d'))->startOfDay();
        $dtSampai = Carbon::parse($this->appliedSampai ?: Carbon::now()->endOfYear()->format('Y-m-d'))->endOfDay();

        $querySM = SuratMasuk::query()
            ->when(! $isLeaderOrSuper && $unitId, fn ($q) => $q->where('unit_kerja_id', $unitId))
            ->whereBetween('tanggal_surat', [$dtDari, $dtSampai]);

        $querySK = SuratKeluar::query()
            ->when(! $isLeaderOrSuper && $unitId, fn ($q) => $q->where('unit_kerja_id', $unitId))
            ->whereBetween('tanggal_surat', [$dtDari, $dtSampai]);

        $queryAD = ArsipDigital::query()
            ->when(! $isLeaderOrSuper && $unitId, fn ($q) => $q->where('unit_kerja_id', $unitId))
            ->whereBetween('tanggal_dokumen', [$dtDari, $dtSampai]);

        $tableData = new Collection;

        if ($this->appliedJenis === '' || $this->appliedJenis === 'Laporan Surat Masuk' || $this->appliedJenis === 'Rekapitulasi Bulanan' || $this->appliedJenis === 'Statistik Tahunan') {
            foreach ((clone $querySM)->latest('tanggal_surat')->get() as $item) {
                $tableData->push([
                    'tanggal' => $item->tanggal_surat ? $item->tanggal_surat->format('d/m/Y') : '-',
                    'raw_date' => $item->tanggal_surat,
                    'nomor_surat' => $item->nomor_surat,
                    'perihal' => $item->perihal,
                    'status' => 'SURAT MASUK',
                    'badge_class' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                ]);
            }
        }

        if ($this->appliedJenis === '' || $this->appliedJenis === 'Laporan Surat Keluar' || $this->appliedJenis === 'Rekapitulasi Bulanan' || $this->appliedJenis === 'Statistik Tahunan') {
            foreach ((clone $querySK)->latest('tanggal_surat')->get() as $item) {
                $tableData->push([
                    'tanggal' => $item->tanggal_surat ? $item->tanggal_surat->format('d/m/Y') : '-',
                    'raw_date' => $item->tanggal_surat,
                    'nomor_surat' => $item->nomor_surat,
                    'perihal' => $item->perihal,
                    'status' => 'SURAT KELUAR',
                    'badge_class' => 'bg-sky-100 text-sky-800 border-sky-200',
                ]);
            }
        }

        if ($this->appliedJenis === '' || $this->appliedJenis === 'Laporan Arsip Digital' || $this->appliedJenis === 'Rekapitulasi Bulanan' || $this->appliedJenis === 'Statistik Tahunan') {
            foreach ((clone $queryAD)->latest('tanggal_dokumen')->get() as $item) {
                $tableData->push([
                    'tanggal' => $item->tanggal_dokumen ? $item->tanggal_dokumen->format('d/m/Y') : '-',
                    'raw_date' => $item->tanggal_dokumen,
                    'nomor_surat' => $item->nomor_dokumen ?: '-',
                    'perihal' => $item->judul,
                    'status' => 'ARSIP DIGITAL',
                    'badge_class' => 'bg-amber-100 text-amber-800 border-amber-200',
                ]);
            }
        }

        $filtered = $tableData->sortByDesc('raw_date')->values();

        if (trim($this->searchTable) !== '') {
            $kw = strtolower(trim($this->searchTable));
            $filtered = $filtered->filter(function ($row) use ($kw) {
                return str_contains(strtolower($row['nomor_surat']), $kw)
                    || str_contains(strtolower($row['perihal']), $kw)
                    || str_contains(strtolower($row['status']), $kw);
            })->values();
        }

        return $filtered;
    }

    public function render(): View
    {
        $pengaturan = PengaturanDokumen::getAktif();

        $allData = $this->getRekapitulasiData();
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $this->perPage;

        $pagedItems = $allData->slice(($page - 1) * $perPage, $perPage)->values();

        $paginatedRekap = new LengthAwarePaginator(
            $pagedItems,
            $allData->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('livewire.laporan.index', [
            'pengaturan' => $pengaturan,
            'paginatedRekap' => $paginatedRekap,
        ]);
    }
}
