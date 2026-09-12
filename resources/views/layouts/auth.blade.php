<!DOCTYPE html>
<html lang="id" class="h-full bg-[#0d7a78]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Portal Tata Usaha dan Persuratan Digital Terpusat Perguruan Tinggi">
    <meta name="author" content="Universitas Digital Nusantara">
    <meta name="theme-color" content="#0d7a78">
    <meta name="robots" content="index, follow">

    <!-- OpenGraph Metadata -->
    <meta property="og:title" content="Masuk ke Portal Persuratan | Arsiparis Surat Digital">
    <meta property="og:description" content="Sistem Tata Usaha & Kearsipan Surat Terpusat Perguruan Tinggi">
    <meta property="og:type" content="website">

    <title>@yield('title', 'Masuk ke Portal') | {{ config('university.name', 'Universitas Digital Nusantara') }}</title>

    <!-- Brand Font: Plus Jakarta Sans (Standar Institusi Nasional) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <style>
        /* Cross-browser compatibility CSS (Edge, Safari, Firefox) */
        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }
        input[type="checkbox"] {
            accent-color: #0d7a78;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-[#0d7a78] flex flex-col justify-between min-h-screen">
    <!-- Skip to main content for keyboard accessibility (a11y) -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-white focus:text-[#0d7a78] focus:rounded-md focus:shadow-lg">
        Lewati ke konten utama
    </a>

    <main id="main-content" class="grow flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-4xl">
            @yield('content')
        </div>
    </main>

    <footer class="w-full py-4 px-4 text-center text-xs text-teal-100/80">
        <p>&copy; {{ date('Y') }} {{ config('university.name', 'Universitas Digital Nusantara') }}. Sistem Tata Usaha &amp; Kearsipan Digital.</p>
    </footer>

    <!-- SweetAlert2 Sweet Toast System -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const AuthToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-xl shadow-xl border border-slate-200 text-xs font-semibold text-slate-800 bg-white'
            }
        });
    </script>
</body>
</html>
