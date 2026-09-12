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
<body class="h-full font-sans antialiased text-slate-800 bg-gradient-to-br from-teal-800 via-teal-700 to-cyan-900 flex flex-col justify-between min-h-screen">
    <!-- Skip to main content for keyboard accessibility (a11y) -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-white focus:text-teal-900 focus:rounded-md focus:shadow-lg">
        Lewati ke konten utama
    </a>

    <main id="main-content" class="grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            @yield('content')
        </div>
    </main>

    <footer class="w-full py-4 px-4 text-center text-xs text-teal-100/70">
        <p>&copy; {{ date('Y') }} {{ config('university.name', 'Universitas') }}. Sistem Tata Usaha & Kearsipan Digital.</p>
    </footer>
</body>
</html>
