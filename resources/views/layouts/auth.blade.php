<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Portal Tata Usaha dan Naskah Dinas Terpusat Perguruan Tinggi">
    <title>@yield('title', 'Masuk') | {{ config('university.name', 'Universitas') }}</title>

    <!-- Preconnect Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-100 flex flex-col justify-between">
    <!-- Skip to main content for keyboard accessibility (a11y) -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-slate-900 focus:text-white focus:rounded-md focus:shadow-lg">
        Lewati ke konten utama
    </a>

    <header class="w-full py-6 px-4 sm:px-6 lg:px-8 border-b border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold text-lg tracking-wider" aria-hidden="true">
                    TU
                </div>
                <div>
                    <h1 class="text-base font-semibold text-slate-900 leading-tight">
                        {{ config('university.name', 'Universitas') }}
                    </h1>
                    <p class="text-xs text-slate-500 font-medium">
                        {{ config('university.subtext', 'Tata Usaha & Kearsipan Naskah Dinas') }}
                    </p>
                </div>
            </div>
            <div class="text-xs text-slate-500 font-medium hidden sm:block">
                Sistem Informasi Arsiparis Digital
            </div>
        </div>
    </header>

    <main id="main-content" class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            @yield('content')
        </div>
    </main>

    <footer class="w-full py-4 px-4 text-center border-t border-slate-200 bg-white text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} {{ config('university.name', 'Universitas') }}. Seluruh hak cipta dilindungi undang-undang.</p>
    </footer>
</body>
</html>
