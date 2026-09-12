<div>
    <!-- Header Halaman Laporan & Statistik -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                {{ \App\Constants\AppConstants::TITLE_LAPORAN }}
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
                {{ \App\Constants\AppConstants::SUBTITLE_LAPORAN }}
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- Export Excel Button -->
            <button
                type="button"
                onclick="confirmExportExcel()"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold shadow-xs transition cursor-pointer active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Export Excel</span>
            </button>

            <!-- Cetak Button -->
            <button
                type="button"
                onclick="confirmCetakPdf()"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0284c7] hover:bg-sky-700 text-white text-xs font-semibold shadow-xs transition cursor-pointer active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-19.126 0C1.08 7.441.312 8.375.312 9.456l.001 6.294A2.25 2.25 0 002.563 18h1.091" />
                </svg>
                <span>Cetak PDF</span>
            </button>
        </div>
    </div>

    <!-- Card Filter Laporan -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs p-5 mb-6 transition-colors">
        <form wire:submit.prevent="tampilkanLaporan" class="space-y-4">
            <!-- Dropdown Jenis Laporan -->
            <div>
                <label for="filter-jenis-laporan" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#0d7a78] dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    <span>Jenis Laporan</span>
                </label>
                <select
                    id="filter-jenis-laporan"
                    wire:model="jenisLaporan"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#0d7a78] focus:border-[#0d7a78] transition bg-white"
                >
                    <option value="">-- Pilih Jenis Laporan --</option>
                    <option value="Laporan Surat Masuk">Laporan Surat Masuk</option>
                    <option value="Laporan Surat Keluar">Laporan Surat Keluar</option>
                    <option value="Laporan Arsip Digital">Laporan Arsip Digital</option>
                    <option value="Rekapitulasi Bulanan">Rekapitulasi Bulanan</option>
                    <option value="Statistik Tahunan">Statistik Tahunan</option>
                </select>
            </div>

            <!-- Periode Dari & Periode Sampai -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="periode-dari" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <span>Periode Dari</span>
                    </label>
                    <input
                        id="periode-dari"
                        type="date"
                        wire:model="periodeDari"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#0d7a78] focus:border-[#0d7a78] transition bg-white"
                    >
                </div>

                <div>
                    <label for="periode-sampai" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <span>Periode Sampai</span>
                    </label>
                    <input
                        id="periode-sampai"
                        type="date"
                        wire:model="periodeSampai"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#0d7a78] focus:border-[#0d7a78] transition bg-white"
                    >
                </div>
            </div>

            <!-- Tombol Filter -->
            <div class="flex items-center justify-center gap-3 pt-2">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="px-6 py-2.5 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold shadow-xs transition cursor-pointer flex items-center gap-2 active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span>Tampilkan Laporan</span>
                </button>

                <button
                    type="button"
                    wire:click="resetFilter"
                    class="px-6 py-2.5 rounded-xl bg-[#1e293b] hover:bg-slate-800 text-white text-xs font-semibold transition cursor-pointer flex items-center gap-2 active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span>Reset Filter</span>
                </button>
            </div>
        </form>
    </div>

    <!-- 4 Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Metric 1: Total Surat Masuk -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs p-5 flex items-center gap-4 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $totalSuratMasuk }}</p>
                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">Total Surat Masuk</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Semua Periode Terpilih</p>
            </div>
        </div>

        <!-- Metric 2: Total Surat Keluar -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs p-5 flex items-center gap-4 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $totalSuratKeluar }}</p>
                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">Total Surat Keluar</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Semua Periode Terpilih</p>
            </div>
        </div>

        <!-- Metric 3: Total Arsip Digital -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs p-5 flex items-center gap-4 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-[#0d7a78] dark:text-teal-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0A2.25 2.25 0 004.5 15h15a2.25 2.25 0 002.25-2.25m-19.5 0v.243a2.25 2.25 0 001.07 1.916l7.5 4.615a2.25 2.25 0 002.36 0l7.5-4.615a2.25 2.25 0 001.07-1.916V12.75" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $totalArsip }}</p>
                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">Total Arsip</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Semua Periode Terpilih</p>
            </div>
        </div>

        <!-- Metric 4: Total Seluruh Dokumen -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs p-5 flex items-center gap-4 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.495 12 4.125 12h2.25c.63 0 1.125.504 1.125 1.125v6.75C7.5 20.496 7.005 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.495-1.125 1.125-1.125h2.25c.63 0 1.125.504 1.125 1.125v11.25c0 .621-.495 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.495-1.125 1.125-1.125h2.25c.63 0 1.125.504 1.125 1.125v15.75c0 .621-.495 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $totalDokumen }}</p>
                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">Total Dokumen</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Semua Periode Terpilih</p>
            </div>
        </div>
    </div>

    <!-- Section Grafik Statistik -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs p-5 mb-6 transition-colors" x-data="{ activeTab: 'bar' }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-700 pb-3 mb-4">
            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#0d7a78] dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.495 12 4.125 12h2.25c.63 0 1.125.504 1.125 1.125v6.75C7.5 20.496 7.005 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.495-1.125 1.125-1.125h2.25c.63 0 1.125.504 1.125 1.125v11.25c0 .621-.495 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.495-1.125 1.125-1.125h2.25c.63 0 1.125.504 1.125 1.125v15.75c0 .621-.495 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                <span>Grafik Statistik Naskah Dinas</span>
            </h3>

            <!-- Tab Buttons Switcher (Bar & Line Chart Only) -->
            <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-700 p-1 rounded-xl text-xs font-semibold">
                <button
                    type="button"
                    @click="activeTab = 'bar'; switchChart('bar')"
                    :class="activeTab === 'bar' ? 'bg-[#0d7a78] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                    class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer"
                >
                    Bar Chart
                </button>
                <button
                    type="button"
                    @click="activeTab = 'line'; switchChart('line')"
                    :class="activeTab === 'line' ? 'bg-[#0d7a78] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                    class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer"
                >
                    Line Chart
                </button>
            </div>
        </div>

        <!-- Canvas ChartJS -->
        <div class="h-64 relative">
            <canvas id="laporanChart" class="w-full h-full"></canvas>
        </div>
    </div>

    <!-- Tabel Data Rekapitulasi Advance Table + Pagination -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs overflow-hidden transition-colors">
        <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">
                Rekapitulasi Data Dokumen
            </h3>

            <div class="flex items-center gap-3">
                <div class="relative min-w-56">
                    <input
                        type="search"
                        wire:model.live.debounce.300ms="searchTable"
                        placeholder="Cari nomor, perihal, status..."
                        class="w-full px-3 py-1.5 pl-8 text-xs border border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0d7a78]"
                    >
                    <svg class="w-3.5 h-3.5 absolute left-2.5 top-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>

                <select wire:model.live="perPage" class="px-2 py-1.5 text-xs border border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-lg">
                    <option value="5">5 / hal</option>
                    <option value="10">10 / hal</option>
                    <option value="25">25 / hal</option>
                    <option value="50">50 / hal</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-[#0d7a78] dark:bg-slate-700 text-white font-semibold text-xs tracking-wider">
                        <th scope="col" class="py-3.5 px-4 w-12 text-center">No</th>
                        <th scope="col" class="py-3.5 px-4 w-32">Tanggal</th>
                        <th scope="col" class="py-3.5 px-4 w-48">Nomor Surat</th>
                        <th scope="col" class="py-3.5 px-4">Perihal</th>
                        <th scope="col" class="py-3.5 px-4 w-40 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-200">
                    @forelse ($paginatedRekap as $index => $row)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                            <td class="py-3 px-4 text-center text-xs font-medium text-slate-500 dark:text-slate-400">
                                {{ $paginatedRekap->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4 text-xs font-medium text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                {{ $row['tanggal'] }}
                            </td>
                            <td class="py-3 px-4 font-semibold text-slate-900 dark:text-slate-100 text-xs font-mono">
                                {{ $row['nomor_surat'] }}
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-800 dark:text-slate-200">
                                {{ $row['perihal'] }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold border {{ $row['badge_class'] }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 text-xs">
                                Tidak ada data rekapitulasi ditemukan untuk kriteria ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
            <span class="text-xs text-slate-500 dark:text-slate-400">
                Menampilkan {{ $paginatedRekap->firstItem() ?? 0 }} - {{ $paginatedRekap->lastItem() ?? 0 }} dari {{ $paginatedRekap->total() }} data
            </span>
            <div>
                {{ $paginatedRekap->links() }}
            </div>
        </div>
    </div>

    <!-- Script ChartJS Integration & Exporter SweetAlert2 Confirmations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let currentChart = null;
        let chartDataStore = {
            masuk: @json(array_values($chartMasukBulanan)),
            keluar: @json(array_values($chartKeluarBulanan)),
            totalSM: {{ $totalSuratMasuk }},
            totalSK: {{ $totalSuratKeluar }},
            totalAD: {{ $totalArsip }}
        };

        function switchChart(type) {
            const ctx = document.getElementById('laporanChart');
            if (!ctx) return;

            if (currentChart) {
                currentChart.destroy();
                currentChart = null;
            }

            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#cbd5e1' : '#475569';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.05)';
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            currentChart = new Chart(ctx, {
                type: type === 'line' ? 'line' : 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Surat Masuk',
                            data: chartDataStore.masuk,
                            backgroundColor: type === 'line' ? 'rgba(2, 132, 199, 0.15)' : '#0284c7',
                            borderColor: '#0284c7',
                            borderWidth: 2,
                            borderRadius: type === 'bar' ? 6 : 0,
                            fill: type === 'line',
                            tension: 0.3
                        },
                        {
                            label: 'Surat Keluar',
                            data: chartDataStore.keluar,
                            backgroundColor: type === 'line' ? 'rgba(16, 185, 129, 0.15)' : '#10b981',
                            borderColor: '#10b981',
                            borderWidth: 2,
                            borderRadius: type === 'bar' ? 6 : 0,
                            fill: type === 'line',
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { color: textColor, font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' } }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: textColor, font: { size: 10 } },
                            grid: { color: gridColor }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: textColor, precision: 0, font: { size: 10 } },
                            grid: { color: gridColor }
                        }
                    }
                }
            });
        }

        function confirmExportExcel() {
            Swal.fire({
                title: 'Konfirmasi Export Excel',
                text: 'Unduh laporan rekapitulasi data persuratan dalam format CSV/Excel (UTF-8 BOM)?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d7a78',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Unduh File',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 dark:border-slate-700 shadow-2xl text-xs font-sans'
                }
            }).then((res) => {
                if (res.isConfirmed) {
                    @this.call('exportExcel');
                }
            });
        }

        function confirmCetakPdf() {
            Swal.fire({
                title: 'Konfirmasi Cetak PDF',
                text: 'Buka pratinjau cetak laporan resmi dengan kop surat universitas?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0284c7',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Buka Pratinjau Cetak',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 dark:border-slate-700 shadow-2xl text-xs font-sans'
                }
            }).then((res) => {
                if (res.isConfirmed) {
                    const url = "{{ route('laporan.cetak', ['jenis' => $appliedJenis, 'dari' => $appliedDari, 'sampai' => $appliedSampai]) }}";
                    window.open(url, '_blank');
                }
            });
        }

        document.addEventListener('livewire:initialized', () => {
            switchChart('bar');

            Livewire.on('update-chart-data', (data) => {
                chartDataStore.masuk = data.masuk;
                chartDataStore.keluar = data.keluar;
                chartDataStore.totalSM = data.masuk.reduce((a, b) => a + b, 0);
                chartDataStore.totalSK = data.keluar.reduce((a, b) => a + b, 0);

                const activeTabBtn = document.querySelector('[x-data]').__x?.$data?.activeTab || 'bar';
                switchChart(activeTabBtn);
            });
        });
    </script>
</div>
