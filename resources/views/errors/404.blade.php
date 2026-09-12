<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100 dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>404 - Dokumen Tidak Ditemukan | Arsiparis Surat Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4 text-slate-800 dark:text-slate-200">
    <div class="max-w-md w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-lg p-6 md:p-8 shadow-sm text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 mb-4">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Galat 404: Tidak Ditemukan</p>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Halaman atau Berkas Tidak Ditemukan</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
            Nomor registrasi naskah dinas, tautan streaming dokumen, atau halaman yang Anda tuju tidak tersedia dalam pangkalan data arsip digital.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url()->previous() }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium border border-slate-300 dark:border-slate-700 rounded text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                Kembali
            </a>
            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-slate-900 hover:bg-slate-800 text-white rounded transition-colors">
                Ke Beranda Utama
            </a>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 text-xs text-slate-500">
            Pastikan tautan atau nomor naskah dinas yang Anda masukkan telah sesuai format agenda kampus.
        </div>
    </div>
</body>
</html>
