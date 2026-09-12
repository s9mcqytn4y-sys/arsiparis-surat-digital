@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-bold text-slate-900">Repositori Arsip Digital</h1>
        <p class="text-sm text-slate-600 mt-1">Penyimpanan dokumen institusi, sertifikat akreditasi, dan SK resmi perguruan tinggi.</p>
    </div>

    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
        <div class="mx-auto w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-[#0d7a78] mb-3">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-900">Repositori Dokumen Institusi</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">Modul manajemen berkas akreditasi dan repositori institusi terenkripsi UUID v7.</p>
    </div>
</div>
@endsection
