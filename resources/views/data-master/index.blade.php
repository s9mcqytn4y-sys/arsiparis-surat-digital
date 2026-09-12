@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-bold text-slate-900">Data Master Universitas</h1>
        <p class="text-sm text-slate-600 mt-1">Konfigurasi unit kerja, master klasifikasi nomor surat, dan daftar pejabat penandatangan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
            <h3 class="text-base font-bold text-slate-900 mb-2">Unit Kerja Kampus</h3>
            <p class="text-xs text-slate-500 mb-4">Daftar Biro, Lembaga, Fakultas, dan Program Studi aktif.</p>
            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-teal-50 text-[#0d7a78]">Terkonfigurasi</span>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
            <h3 class="text-base font-bold text-slate-900 mb-2">Data Pegawai & Pejabat</h3>
            <p class="text-xs text-slate-500 mb-4">Data NIP, NIDN, dan jabatan kepegawaian.</p>
            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-teal-50 text-[#0d7a78]">Terkonfigurasi</span>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
            <h3 class="text-base font-bold text-slate-900 mb-2">Master Format Nomor Surat</h3>
            <p class="text-xs text-slate-500 mb-4">Pola penomoran dinas anti-race condition dengan penguncian pesimistik.</p>
            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-teal-50 text-[#0d7a78]">Terkonfigurasi</span>
        </div>
    </div>
</div>
@endsection
