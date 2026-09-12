<div class="space-y-6">
    <!-- Header Dashboard Operasional -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-xs border border-slate-200/80 dark:border-slate-700/80 transition-colors">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">
                {{ \App\Constants\AppConstants::TITLE_DASHBOARD }}
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
                {{ \App\Constants\AppConstants::SUBTITLE_DASHBOARD }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a
                href="{{ route('laporan.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold shadow-xs transition-all active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.495 12 4.125 12h2.25c.63 0 1.125.504 1.125 1.125v6.75C7.5 20.496 7.005 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.495-1.125 1.125-1.125h2.25c.63 0 1.125.504 1.125 1.125v11.25c0 .621-.495 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.495-1.125 1.125-1.125h2.25c.63 0 1.125.504 1.125 1.125v15.75c0 .621-.495 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                <span>Laporan & Analitik</span>
            </a>
        </div>
    </div>

    <!-- Skeleton Loading State -->
    <div wire:loading class="w-full">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            @for ($i = 0; $i < 4; $i++)
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 animate-pulse flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-200 dark:bg-slate-700"></div>
                    <div class="space-y-2 grow">
                        <div class="h-6 bg-slate-200 dark:bg-slate-700 rounded w-1/3"></div>
                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-2/3"></div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Quick Stats Grid (Clean Flat Institutional Cards) -->
    <div wire:loading.remove class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Surat Masuk Card -->
        <a href="{{ route('surat-masuk.index') }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-xs border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-1 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 flex items-center justify-center shadow-xs group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                        {{ $totalMasuk }}
                    </div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
                        Surat Masuk
                    </div>
                </div>
            </div>
        </a>

        <!-- Surat Keluar Card -->
        <a href="{{ route('surat-keluar.index') }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-xs border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-1 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-xs group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                        {{ $totalKeluar }}
                    </div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
                        Surat Keluar
                    </div>
                </div>
            </div>
        </a>

        <!-- Arsip Digital Card -->
        <a href="{{ route('arsip-digital.index') }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-xs border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-1 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-[#0d7a78] dark:text-teal-400 flex items-center justify-center shadow-xs group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                        {{ $totalArsip }}
                    </div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
                        Arsip Digital
                    </div>
                </div>
            </div>
        </a>

        <!-- Data Pegawai Card -->
        <a href="{{ route('data-master.index') }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-xs border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-1 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-cyan-50 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400 flex items-center justify-center shadow-xs group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                        {{ $totalPegawai }}
                    </div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
                        Pegawai Terdaftar
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Section Aksi Cepat (Presisi Prototype Gambar 1) -->
    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-xs border border-slate-200/80 dark:border-slate-700/80 transition-colors" aria-labelledby="heading-aksi-cepat">
        <h2 id="heading-aksi-cepat" class="text-base font-bold text-slate-900 dark:text-slate-100 mb-4 tracking-tight">
            Aksi Cepat
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Tombol Input Surat Masuk -->
            <a
                href="{{ route('surat-masuk.index') }}"
                class="flex flex-col items-center justify-center p-7 rounded-2xl bg-[#0b6361] dark:bg-[#0d7a78] hover:bg-[#084f4d] dark:hover:bg-[#0a5c5a] text-white transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-1 group cursor-pointer active:scale-95"
            >
                <div class="w-12 h-12 rounded-xl bg-teal-800/40 border border-teal-500/30 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-teal-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <span class="text-xs font-bold tracking-wide">Input Surat Masuk</span>
            </a>

            <!-- Tombol Input Surat Keluar -->
            <a
                href="{{ route('surat-keluar.index') }}"
                class="flex flex-col items-center justify-center p-7 rounded-2xl bg-[#0b6361] dark:bg-[#0d7a78] hover:bg-[#084f4d] dark:hover:bg-[#0a5c5a] text-white transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-1 group cursor-pointer active:scale-95"
            >
                <div class="w-12 h-12 rounded-xl bg-teal-800/40 border border-teal-500/30 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-teal-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </div>
                <span class="text-xs font-bold tracking-wide">Input Surat Keluar</span>
            </a>

            <!-- Tombol Upload Arsip -->
            <a
                href="{{ route('arsip-digital.index') }}"
                class="flex flex-col items-center justify-center p-7 rounded-2xl bg-[#084f4d] dark:bg-[#0b6361] hover:bg-[#063b3d] dark:hover:bg-[#084f4d] text-white transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-1 group cursor-pointer active:scale-95"
            >
                <div class="w-12 h-12 rounded-xl bg-teal-800/40 border border-teal-500/30 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-teal-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                </div>
                <span class="text-xs font-bold tracking-wide">Upload Arsip</span>
            </a>
        </div>
    </section>

    <!-- Section Aktivitas Terbaru -->
    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-xs border border-slate-200/80 dark:border-slate-700/80 transition-colors" aria-labelledby="heading-aktivitas-terbaru">
        <div class="flex items-center justify-between mb-4">
            <h2 id="heading-aktivitas-terbaru" class="text-base font-bold text-slate-900 dark:text-slate-100 tracking-tight">
                Aktivitas Terbaru
            </h2>
            <a href="{{ route('surat-masuk.index') }}" class="text-xs font-semibold text-[#0d7a78] dark:text-teal-400 hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
            <table class="w-full text-left text-sm">
                <caption class="sr-only">Tabel Aktivitas Surat Terkini</caption>
                <thead class="bg-[#0d7a78] dark:bg-slate-700 text-white text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 w-12 text-center">NO</th>
                        <th scope="col" class="px-5 py-3.5 w-48">NOMOR SURAT</th>
                        <th scope="col" class="px-5 py-3.5 w-32">TANGGAL</th>
                        <th scope="col" class="px-5 py-3.5 w-64">PENGIRIM / INSTANSI</th>
                        <th scope="col" class="px-5 py-3.5">PERIHAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100">
                    @forelse($aktivitasTerbaru as $index => $surat)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-5 py-3.5 text-xs text-slate-500 dark:text-slate-400 text-center font-medium">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-slate-100 text-xs font-mono">
                                {{ $surat->nomor_surat }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                {{ $surat->tanggal_terima ? $surat->tanggal_terima->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-xs font-medium text-slate-800 dark:text-slate-200">
                                {{ $surat->pengirim }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-800 dark:text-slate-200">
                                {{ $surat->perihal }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-xs text-slate-400">
                                Belum ada data surat tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
