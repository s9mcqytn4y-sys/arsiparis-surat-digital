<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100 dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>500 - Kendala Sistem Internal | Arsiparis Surat Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4 text-slate-800 dark:text-slate-200">
    <div class="max-w-md w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-lg p-6 md:p-8 shadow-sm text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-rose-100 dark:bg-rose-950/60 border border-rose-300 dark:border-rose-800 text-rose-700 dark:text-rose-400 mb-4">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <p class="text-xs font-semibold uppercase tracking-wider text-rose-700 dark:text-rose-400 mb-1">Galat 500: Kendala Internal</p>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Terjadi Kendala pada Sistem</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
            Sistem tata usaha persuratan sedang mengalami kendala teknis saat memproses permintaan Anda. Peristiwa ini telah dicatat dalam log audit untuk segera ditindaklanjuti.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url()->previous() }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium border border-slate-300 dark:border-slate-700 rounded text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                Muat Ulang Halaman
            </a>
            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-slate-900 hover:bg-slate-800 text-white rounded transition-colors">
                Ke Beranda Utama
            </a>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 text-xs text-slate-500">
            Pusat Data & Sistem Informasi Perguruan Tinggi
        </div>
    </div>
</body>
</html>
