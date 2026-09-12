<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Portal Tata Usaha dan Naskah Dinas Terpusat Perguruan Tinggi">
    <title>@yield('title', 'Beranda') | {{ config('university.name', 'Universitas') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen" x-data="{ sidebarOpen: false }">
    <!-- Skip to main content (a11y) -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-slate-900 focus:text-white focus:rounded-md focus:shadow-lg">
        Lewati ke konten utama
    </a>

    <!-- Top Navigation Header -->
    <header class="w-full bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Mobile Menu Button & Logo -->
                <div class="flex items-center gap-3">
                    <button 
                        type="button" 
                        @click="sidebarOpen = !sidebarOpen" 
                        aria-label="Buka menu navigasi"
                        class="md:hidden p-2 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-slate-900 min-h-[44px] min-w-[44px] flex items-center justify-center"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold text-sm" aria-hidden="true">
                            TU
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-slate-900 leading-tight">
                                {{ config('university.name', 'Universitas') }}
                            </span>
                            <span class="block text-[11px] text-slate-500 font-medium">
                                Tata Usaha & Naskah Dinas Digital
                            </span>
                        </div>
                    </a>
                </div>

                <!-- User Profile & Action Header -->
                <div class="flex items-center gap-4">
                    @auth
                        <!-- In-App Notification Center Badge -->
                        @livewire('common.notification-badge')

                        <div class="text-right hidden sm:block">
                            <div class="text-xs font-bold text-slate-900 leading-tight">
                                {{ auth()->user()->name }}
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium flex items-center justify-end gap-1.5">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ auth()->user()->roles->first()?->name ?? 'Pengguna' }}
                                </span>
                                <span>&bull;</span>
                                <span>{{ auth()->user()->unitKerja?->nama_unit ?? 'Pusat' }}</span>
                            </div>
                        </div>

                        <!-- Tombol Keluar -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button 
                                type="submit" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-slate-300 rounded-md text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 transition-colors min-h-[36px]"
                            >
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="hidden sm:inline">{{ __('persuratan.auth.logout') }}</span>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container with Sidebar -->
    <div class="flex-grow flex max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 gap-6">
        <!-- Sidebar Navigation -->
        <aside 
            :class="sidebarOpen ? 'block fixed inset-0 z-40 bg-slate-900/50 md:relative md:inset-auto md:bg-transparent md:block' : 'hidden md:block'"
            class="w-64 flex-shrink-0 no-print"
        >
            <nav 
                class="bg-white rounded-lg border border-slate-200 p-4 space-y-1.5 shadow-xs" 
                aria-label="Menu Utama Persuratan"
            >
                <div class="px-3 py-2 text-[11px] font-bold tracking-wider text-slate-400 uppercase">
                    Modul Tata Usaha
                </div>

                <a 
                    href="{{ route('dashboard') }}" 
                    class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>{{ __('persuratan.nav.dashboard') }}</span>
                </a>

                <a 
                    href="{{ route('surat-masuk.index') }}" 
                    class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('surat-masuk.*') ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <span>{{ __('persuratan.nav.surat_masuk') }}</span>
                </a>

                <a 
                    href="{{ route('surat-keluar.index') }}" 
                    class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('surat-keluar.*') ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span>{{ __('persuratan.nav.surat_keluar') }}</span>
                </a>

                @if(auth()->user()?->hasRole('super_admin'))
                    <div class="pt-4 px-3 py-2 text-[11px] font-bold tracking-wider text-slate-400 uppercase border-t border-slate-100">
                        Administrasi Pusat
                    </div>
                    <a 
                        href="/admin" 
                        target="_blank"
                        class="flex items-center justify-between px-3 py-2.5 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-100 transition-colors"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Panel Admin IT</span>
                        </div>
                        <span class="text-[10px] bg-amber-100 text-amber-800 font-semibold px-1.5 py-0.5 rounded">Filament</span>
                    </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main id="main-content" class="flex-grow min-w-0">
            <!-- Session Flash Alerts -->
            @if(session('success'))
                <div class="mb-4 p-4 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-center justify-between" role="status">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 rounded-md bg-rose-50 border border-rose-200 text-rose-900 text-sm flex items-center justify-between" role="alert">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
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
        <p>&copy; {{ date('Y') }} {{ config('university.name', 'Universitas') }}. Tata Usaha & Kearsipan Naskah Dinas Terpusat.</p>
    </footer>

    @livewireScripts
</body>
</html>
