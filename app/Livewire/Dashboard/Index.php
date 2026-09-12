<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\ArsipDigital;
use App\Models\Pegawai;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard Surat & Kearsipan')]
class Index extends Component
{
    public function render(): View
    {
        $totalMasuk = SuratMasuk::count();
        $totalKeluar = SuratKeluar::count();
        $totalArsip = ArsipDigital::count();
        $totalPegawai = Pegawai::count();

        $aktivitasTerbaru = SuratMasuk::with('unitKerja')
            ->latest('tanggal_terima')
            ->take(5)
            ->get();

        return view('livewire.dashboard.index', [
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'totalArsip' => $totalArsip,
            'totalPegawai' => $totalPegawai,
            'aktivitasTerbaru' => $aktivitasTerbaru,
        ]);
    }
}
