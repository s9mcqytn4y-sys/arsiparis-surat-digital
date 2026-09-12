<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Portal Tata Usaha dan Naskah Dinas Terpusat Perguruan Tinggi">
    <title>@yield('title', 'Beranda') | {{ config('university.name', 'Arsiparis Surat Digital') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-[#f4f7f6] flex flex-col min-h-screen">
    <!-- Skip to main content (a11y) -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-slate-900 focus:text-white focus:rounded-md focus:shadow-lg">
        Lewati ke konten utama
    </a>

    <!-- Top Navigation Header (Prototype Style: Deep Teal Bar) -->
    <header class="w-full bg-[#0d7a78] text-white sticky top-0 z-30 shadow-md no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & University Title -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-lg bg-amber-400 text-teal-950 flex items-center justify-center font-black text-xl shadow-xs" aria-hidden="true">
                            <svg class="w-6 h-6 text-teal-900" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm0 2.84L18 11v7h-2v-6H8v6H6v-7l6-5.16zM12 8a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"/>
                            </svg>
                        </div>
                        <span class="text-base sm:text-lg font-bold tracking-tight text-white">
                            Arsiparis Surat Digital
                        </span>
                    </a>

                    <!-- Top Horizontal Navigation Links (Desktop) -->
                    <nav class="hidden md:flex items-center gap-1.5" aria-label="Navigasi Utama">
                        <a 
                            href="{{ route('dashboard') }}" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-[#0a5c5a] text-white shadow-inner' : 'text-teal-100 hover:bg-[#118b89] hover:text-white' }}"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <a 
                            href="{{ route('surat-masuk.index') }}" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('surat-masuk.*') ? 'bg-[#0a5c5a] text-white shadow-inner' : 'text-teal-100 hover:bg-[#118b89] hover:text-white' }}"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <span>Surat Masuk</span>
                        </a>

                        <a 
                            href="{{ route('surat-keluar.index') }}" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('surat-keluar.*') ? 'bg-[#0a5c5a] text-white shadow-inner' : 'text-teal-100 hover:bg-[#118b89] hover:text-white' }}"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span>Surat Keluar</span>
                        </a>

                        <a 
                            href="{{ route('dashboard') }}" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold text-teal-100 hover:bg-[#118b89] hover:text-white transition-all"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <span>Arsip Digital</span>
                        </a>

                        <a 
                            href="{{ route('dashboard') }}" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold text-teal-100 hover:bg-[#118b89] hover:text-white transition-all"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Laporan</span>
                        </a>

                        @if(auth()->user()?->hasRole('super_admin'))
                            <a 
                                href="/admin" 
                                target="_blank"
                                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold text-teal-100 hover:bg-[#118b89] hover:text-white transition-all"
                            >
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7zM4 12h16" />
                                </svg>
                                <span>Data Master</span>
                            </a>
                        @endif
                    </nav>
                </div>

                <!-- User Profile & Action Header -->
                <div class="flex items-center gap-3">
                    @auth
                        <!-- In-App Notification Center Badge -->
                        @livewire('common.notification-badge')

                        <!-- User Pill Button (Prototype Style: Green Pill with Yellow Icon) -->
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#0a5c5a] border border-teal-600/50 text-white text-xs font-medium">
                            <span class="w-5 h-5 rounded-full bg-amber-400 text-teal-950 flex items-center justify-center font-bold text-[10px]" aria-hidden="true">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                        </div>

                        <!-- Tombol Keluar (Prototype Style: Rounded Square Button) -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button 
                                type="submit" 
                                aria-label="Keluar dari sistem"
                                class="p-2 rounded-lg bg-[#0a5c5a] hover:bg-rose-700 text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white min-h-9 min-w-9 flex items-center justify-center"
                            >
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Horizontal Subnav -->
        <div class="md:hidden border-t border-teal-700/60 bg-[#0c6e6c] px-4 py-2 flex items-center justify-between overflow-x-auto text-xs font-semibold gap-2">
            <a href="{{ route('dashboard') }}" class="px-2.5 py-1.5 rounded {{ request()->routeIs('dashboard') ? 'bg-[#084846] text-white' : 'text-teal-100' }}">Dashboard</a>
            <a href="{{ route('surat-masuk.index') }}" class="px-2.5 py-1.5 rounded {{ request()->routeIs('surat-masuk.*') ? 'bg-[#084846] text-white' : 'text-teal-100' }}">Surat Masuk</a>
            <a href="{{ route('surat-keluar.index') }}" class="px-2.5 py-1.5 rounded {{ request()->routeIs('surat-keluar.*') ? 'bg-[#084846] text-white' : 'text-teal-100' }}">Surat Keluar</a>
            <a href="{{ route('dashboard') }}" class="px-2.5 py-1.5 rounded text-teal-100">Arsip</a>
            <a href="{{ route('dashboard') }}" class="px-2.5 py-1.5 rounded text-teal-100">Laporan</a>
        </div>
    </header>

    <!-- Main Container (Full-Width Clean Layout) -->
    <div class="grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Main Content Area -->
        <main id="main-content" class="min-w-0">
            <!-- Session Flash Alerts -->
            @if(session('success'))
                <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-center justify-between shadow-xs" role="status">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-sm flex items-center justify-between shadow-xs" role="alert">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Content Slot -->
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    <!-- University Footer -->
    <footer class="w-full py-4 px-4 text-center border-t border-slate-200 bg-white text-xs text-slate-500 no-print mt-auto">
        <p>&copy; {{ date('Y') }} Arsiparis Surat Digital. Tata Usaha & Kearsipan Naskah Dinas Terpusat.</p>
    </footer>

    @livewireScripts
</body>
</html>
