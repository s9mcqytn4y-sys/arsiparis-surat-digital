<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CetakBukuAgendaController extends Controller
{
    public function __invoke(Request $request): View|Response|StreamedResponse
    {
        Gate::authorize('viewAny', SuratMasuk::class);

        $user = $request->user();

        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());
        $unitId = $request->query('unit_id');
        $exportFormat = $request->query('export'); // 'pdf' | 'csv' | null (print HTML)

        $query = SuratMasuk::query()
            ->with(['unitKerja', 'pembuat'])
            ->whereBetween('tanggal_terima', [$startDate, $endDate])
            ->orderBy('nomor_agenda', 'asc');

        // Scoping per unit kerja bila bukan super_admin / pimpinan rektorat
        if (! $user->hasRole(['super_admin', 'rektor', 'wakil_rektor']) && $user->unit_kerja_id) {
            $query->where('unit_kerja_id', $user->unit_kerja_id);
            $activeUnit = UnitKerja::find($user->unit_kerja_id);
        } elseif ($unitId) {
            $query->where('unit_kerja_id', $unitId);
            $activeUnit = UnitKerja::find($unitId);
        } else {
            $activeUnit = null;
        }

        $daftarSurat = $query->get();

        // 1. Ekspor Format CSV
        if ($exportFormat === 'csv') {
            $filename = sprintf('buku-agenda-surat-masuk-%s-sd-%s.csv', $startDate, $endDate);

            return response()->streamDownload(function () use ($daftarSurat): void {
                $handle = fopen('php://output', 'w');
                if ($handle === false) {
                    return;
                }

                // UTF-8 BOM untuk Microsoft Excel
                fwrite($handle, "\xEF\xBB\xBF");

                // Header Kolom
                fputcsv($handle, [
                    'No. Agenda',
                    'No. Surat',
                    'Tanggal Terima',
                    'Tanggal Surat',
                    'Pengirim',
                    'Perihal',
                    'Unit Kerja Pengelola',
                ]);

                foreach ($daftarSurat as $surat) {
                    fputcsv($handle, [
                        $surat->nomor_agenda,
                        $surat->nomor_surat,
                        $surat->tanggal_terima?->format('Y-m-d') ?? '',
                        $surat->tanggal_surat?->format('Y-m-d') ?? '',
                        $surat->pengirim,
                        $surat->perihal,
                        $surat->unitKerja?->nama_unit ?? '-',
                    ]);
                }

                fclose($handle);
            }, $filename, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]);
        }

        // Data payload untuk Cetak & PDF
        $viewData = [
            'daftarSurat' => $daftarSurat,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'activeUnit' => $activeUnit,
        ];

        // 2. Ekspor Format PDF (DomPDF Landscape A4)
        if ($exportFormat === 'pdf') {
            $pdf = Pdf::loadView('cetak.buku-agenda', $viewData)
                ->setPaper('a4', 'landscape');

            $filename = sprintf('buku-agenda-surat-masuk-%s.pdf', now()->format('YmdHis'));

            return $pdf->stream($filename);
        }

        // 3. Tampilan Pratinjau Cetak Browser (@media print)
        return view('cetak.buku-agenda', $viewData);
    }
}
