<!DOCTYPE html>
<html lang="id" class="h-full" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>403 - Hak Akses Ditolak | Arsiparis Surat Digital</title>

    <script>
        (function() {
            var savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4 text-slate-800 dark:text-slate-100 bg-[#f4f7f6] dark:bg-slate-900 transition-colors duration-200">
    <div class="max-w-md w-full bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 rounded-2xl p-6 md:p-8 shadow-xl text-center transition-colors">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-amber-100 dark:bg-amber-950/60 border border-amber-300 dark:border-amber-800 text-amber-700 dark:text-amber-400 mb-4">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <p class="text-xs font-semibold uppercase tracking-wider text-amber-700 dark:text-amber-400 mb-1">Galat 403: Terlarang</p>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Akses Ditolak</h1>
        <p class="text-sm text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
            Akun Anda tidak memiliki wewenang atau hak akses untuk melihat naskah dinas, dokumen privat, atau menu unit kerja ini.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url()->previous() }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 text-xs font-semibold border border-slate-300 dark:border-slate-600 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
                Kembali ke Halaman Sebelumnya
            </a>
            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 text-xs font-semibold bg-[#0d7a78] hover:bg-[#0a5c5a] text-white rounded-xl transition shadow-xs cursor-pointer">
                Ke Beranda Utama
            </a>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-700 text-xs text-slate-500 dark:text-slate-400">
            Jika ini adalah kekeliruan tugas kedinasan, silakan hubungi Administrator Sistem Tata Usaha Rektorat.
        </div>
    </div>
</body>
</html>
