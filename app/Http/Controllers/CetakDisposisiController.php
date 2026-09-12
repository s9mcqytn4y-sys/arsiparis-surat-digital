<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class CetakDisposisiController extends Controller
{
    public function __invoke(Request $request, SuratMasuk $surat): View|Response
    {
        Gate::authorize('view', $surat);

        $surat->load(['unitKerja', 'disposisiPegawai', 'pembuat']);

        if ($request->query('export') === 'pdf') {
            $pdf = Pdf::loadView('cetak.lembar-disposisi', [
                'surat' => $surat,
            ])->setPaper('a4', 'portrait');

            return $pdf->stream(sprintf('disposisi-%s.pdf', str_replace('/', '-', $surat->nomor_agenda)));
        }

        return view('cetak.lembar-disposisi', [
            'surat' => $surat,
        ]);
    }
}
