@extends('layouts.app')

@section('title', 'Beranda Utama Tata Usaha')

@section('header_title', 'Dasbor Tata Usaha & Kearsipan')

@section('content')
<div class="space-y-6">
    <!-- Greeting Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-lg p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 mb-2">
                    Unit Kerja: {{ auth()->user()->unitKerja->nama_unit ?? 'Rektorat Terpusat' }}
                </span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                    Selamat Bertugas, {{ auth()->user()->name }}
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Sistem Tata Kelola Naskah Dinas Digital Perguruan Tinggi terintegrasi buku agenda resmi dan nomor urut anti-duplikasi.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('surat-masuk.index') }}" class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 rounded hover:bg-slate-800 dark:hover:bg-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Catat Surat Masuk
                </a>
                <a href="{{ route('surat-keluar.index') }}" class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Registrasi Surat Keluar
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-lg p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Surat Masuk</span>
                <span class="p-2 rounded bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-3">{{ \App\Models\SuratMasuk::count() }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Total teregistrasi di unit</p>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-lg p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Perlu Disposisi</span>
                <span class="p-2 rounded bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-amber-700 dark:text-amber-400 mt-3">
                {{ \App\Models\SuratMasuk::where('status_disposisi', \App\Enums\StatusDisposisi::MENUNGGU)->count() }}
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Menunggu telaah pimpinan</p>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-lg p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Surat Keluar</span>
                <span class="p-2 rounded bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-3">{{ \App\Models\SuratKeluar::count() }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Nomor resmi diterbitkan</p>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-lg p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pola Nomor Surat</span>
                <span class="p-2 rounded bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-3">{{ \App\Models\MasterNomorSurat::count() }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Format penomoran aktif</p>
        </div>
    </div>

    <!-- Recent Letters Table -->
    <section class="bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden" aria-labelledby="heading-agenda-terkini">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 id="heading-agenda-terkini" class="text-base font-bold text-slate-900 dark:text-white">
                Daftar Naskah Dinas Terkini
            </h3>
            <a href="{{ route('surat-masuk.index') }}" class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                Lihat Seluruh Agenda &rarr;
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <caption class="sr-only">Tabel Agenda Surat Masuk Terkini</caption>
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-xs uppercase font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-3">No. Agenda</th>
                        <th scope="col" class="px-6 py-3">Nomor Surat Asli</th>
                        <th scope="col" class="px-6 py-3">Pengirim</th>
                        <th scope="col" class="px-6 py-3">Perihal</th>
                        <th scope="col" class="px-6 py-3">Tgl. Diterima</th>
                        <th scope="col" class="px-6 py-3">Status Disposisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse(\App\Models\SuratMasuk::with('unitKerja')->latest('tanggal_terima')->take(5)->get() as $surat)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="px-6 py-3 font-mono font-medium text-slate-900 dark:text-white text-xs">
                                {{ $surat->nomor_agenda }}
                            </td>
                            <td class="px-6 py-3 text-slate-700 dark:text-slate-300 text-xs">
                                {{ $surat->nomor_surat }}
                            </td>
                            <td class="px-6 py-3 text-slate-700 dark:text-slate-300">
                                {{ $surat->pengirim }}
                            </td>
                            <td class="px-6 py-3 text-slate-900 dark:text-slate-100 max-w-xs truncate">
                                {{ $surat->perihal }}
                            </td>
                            <td class="px-6 py-3 text-slate-600 dark:text-slate-400 text-xs">
                                <time datetime="{{ $surat->tanggal_terima->toDateString() }}">
                                    {{ $surat->tanggal_terima->translatedFormat('d M Y') }}
                                </time>
                            </td>
                            <td class="px-6 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                                    {{ $surat->status_disposisi === \App\Enums\StatusDisposisi::MENUNGGU ? 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-400' : '' }}
                                    {{ $surat->status_disposisi === \App\Enums\StatusDisposisi::DITERUSKAN ? 'bg-sky-100 dark:bg-sky-950/60 text-sky-800 dark:text-sky-400' : '' }}
                                    {{ $surat->status_disposisi === \App\Enums\StatusDisposisi::SELESAI ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-400' : '' }}
                                ">
                                    {{ $surat->status_disposisi->label() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                Belum ada naskah dinas masuk yang tercatat di unit kerja ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
