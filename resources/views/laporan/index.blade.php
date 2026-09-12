@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-bold text-slate-900">Laporan & Ekspor Kearsipan</h1>
        <p class="text-sm text-slate-600 mt-1">Cetak buku agenda dinas, rekapitulasi korespondensi, dan laporan distribusi disposisi.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
            <h3 class="text-base font-bold text-slate-900 mb-2">Buku Agenda Naskah Masuk</h3>
            <p class="text-xs text-slate-500 mb-4">Cetak rekapan buku agenda naskah dinas masuk sesuai format resmi kearsipan perguruan tinggi.</p>
            <a href="{{ route('cetak.buku-agenda') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-[#0d7a78] px-4 py-2.5 text-xs font-semibold text-white hover:bg-[#0b6563] transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.049-.37-2.14-.37-3.254a8.25 8.25 0 1 1 16.5 0c0 1.114-.13 2.205-.37 3.254M12 18v3.75m-4.5 0h9M3 13.5h18" />
                </svg>
                <span>Buka Lembar Agenda Cetak</span>
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
            <h3 class="text-base font-bold text-slate-900 mb-2">Rekapitulasi Naskah Keluar</h3>
            <p class="text-xs text-slate-500 mb-4">Rekapitulasi register penomoran naskah keluar dan jenis naskah dinas.</p>
            <button type="button" disabled class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-xs font-semibold text-slate-400 cursor-not-allowed">
                <span>Segera Hadir</span>
            </button>
        </div>
    </div>
</div>
@endsection
