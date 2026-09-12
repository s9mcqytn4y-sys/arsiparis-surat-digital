@extends('layouts.app')

@section('title', 'Dasbor Tata Usaha')

@section('content')
<div class="space-y-6">
    <!-- Quick Stats Grid (Prototype Style: 4 Rounded Cards with Soft Icon Accents) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Surat Masuk Card -->
        <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-100 flex items-center justify-between transition hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-600 text-white flex items-center justify-center shadow-xs">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">
                        {{ \App\Models\SuratMasuk::count() }}
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mt-0.5">
                        Surat Masuk
                    </div>
                </div>
            </div>
            <div class="w-12 h-12 rounded-full bg-slate-50 opacity-60"></div>
        </div>

        <!-- Surat Keluar Card -->
        <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-100 flex items-center justify-between transition hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-xs">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">
                        {{ \App\Models\SuratKeluar::count() }}
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mt-0.5">
                        Surat Keluar
                    </div>
                </div>
            </div>
            <div class="w-12 h-12 rounded-full bg-slate-50 opacity-60"></div>
        </div>

        <!-- Arsip Digital Card -->
        <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-100 flex items-center justify-between transition hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#0d7a78] text-white flex items-center justify-center shadow-xs">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">
                        {{ \App\Models\ArsipDigital::count() }}
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mt-0.5">
                        Arsip Digital
                    </div>
                </div>
            </div>
            <div class="w-12 h-12 rounded-full bg-slate-50 opacity-60"></div>
        </div>

        <!-- Data Pegawai Card -->
        <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-100 flex items-center justify-between transition hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-cyan-700 text-white flex items-center justify-center shadow-xs">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">
                        {{ \App\Models\Pegawai::count() }}
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mt-0.5">
                        Data Pegawai
                    </div>
                </div>
            </div>
            <div class="w-12 h-12 rounded-full bg-slate-50 opacity-60"></div>
        </div>
    </div>

    <!-- Section Aksi Cepat (Prototype Style: 3 Large Teal Action Banners) -->
    <section class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100" aria-labelledby="heading-aksi-cepat">
        <h2 id="heading-aksi-cepat" class="text-base font-bold text-slate-900 mb-4">
            Aksi Cepat
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Tombol Input Surat Masuk -->
            <a 
                href="{{ route('surat-masuk.index') }}" 
                class="flex flex-col items-center justify-center p-5 rounded-xl bg-gradient-to-r from-teal-700 to-emerald-700 text-white hover:from-teal-800 hover:to-emerald-800 transition shadow-sm group"
            >
                <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <span class="text-sm font-semibold tracking-wide">Input Surat Masuk</span>
            </a>

            <!-- Tombol Input Surat Keluar -->
            <a 
                href="{{ route('surat-keluar.index') }}" 
                class="flex flex-col items-center justify-center p-5 rounded-xl bg-gradient-to-r from-teal-700 to-cyan-700 text-white hover:from-teal-800 hover:to-cyan-800 transition shadow-sm group"
            >
                <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </div>
                <span class="text-sm font-semibold tracking-wide">Input Surat Keluar</span>
            </a>

            <!-- Tombol Upload Arsip -->
            <a 
                href="{{ route('dashboard') }}" 
                class="flex flex-col items-center justify-center p-5 rounded-xl bg-gradient-to-r from-cyan-800 to-teal-800 text-white hover:from-cyan-900 hover:to-teal-900 transition shadow-sm group"
            >
                <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                </div>
                <span class="text-sm font-semibold tracking-wide">Upload Arsip</span>
            </a>
        </div>
    </section>

    <!-- Section Aktivitas Terbaru (Prototype Style: Clean Table) -->
    <section class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100" aria-labelledby="heading-aktivitas-terbaru">
        <div class="flex items-center justify-between mb-4">
            <h2 id="heading-aktivitas-terbaru" class="text-base font-bold text-slate-900">
                Aktivitas Terbaru
            </h2>
            <a href="{{ route('surat-masuk.index') }}" class="text-xs font-semibold text-[#0d7a78] hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left text-sm">
                <caption class="sr-only">Tabel Aktivitas Naskah Dinas Terkini</caption>
                <thead class="bg-[#0d7a78] text-white text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-5 py-3.5">No</th>
                        <th scope="col" class="px-5 py-3.5">Nomor Surat</th>
                        <th scope="col" class="px-5 py-3.5">Tanggal</th>
                        <th scope="col" class="px-5 py-3.5">Pengirim / Tujuan</th>
                        <th scope="col" class="px-5 py-3.5">Perihal</th>
                        <th scope="col" class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse(\App\Models\SuratMasuk::with('unitKerja')->latest('tanggal_terima')->take(5)->get() as $index => $surat)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 text-xs text-slate-500">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-5 py-3.5 font-medium text-slate-900 text-xs font-mono">
                                {{ $surat->nomor_surat }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600 whitespace-nowrap">
                                {{ $surat->tanggal_terima->translatedFormat('d/m/Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-700">
                                {{ $surat->pengirim }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-900 max-w-xs truncate">
                                {{ $surat->perihal }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold
                                    {{ $surat->status_disposisi === \App\Enums\StatusDisposisi::MENUNGGU ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $surat->status_disposisi === \App\Enums\StatusDisposisi::DITERUSKAN ? 'bg-sky-100 text-sky-800' : '' }}
                                    {{ $surat->status_disposisi === \App\Enums\StatusDisposisi::SELESAI ? 'bg-emerald-100 text-emerald-800' : '' }}
                                ">
                                    {{ $surat->status_disposisi->label() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-xs text-slate-500">
                                Belum ada data naskah dinas tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
