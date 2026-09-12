<!DOCTYPE html>
<html lang="id" class="h-full" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Portal Tata Usaha dan Persuratan Digital Terpusat Perguruan Tinggi">
    <meta name="author" content="Universitas Digital Nusantara">
    <meta name="theme-color" content="#0d7a78">
    <meta name="robots" content="index, follow">

    <!-- Anti-Jitter Instant Theme Application -->
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

    <!-- OpenGraph SEO Metadata -->
    <meta property="og:title" content="{{ config('university.name', 'Arsiparis Surat Digital') }}">
    <meta property="og:description" content="Portal Tata Usaha & Kearsipan Surat Terpusat">
    <meta property="og:type" content="website">

    <title>@yield('title', 'Beranda') | {{ config('university.name', 'Arsiparis Surat Digital') }}</title>

    <!-- Brand Font: Plus Jakarta Sans (Standar Institusi Nasional) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-800 dark:text-slate-100 bg-[#f4f7f6] dark:bg-slate-900 flex flex-col min-h-screen transition-colors duration-200">
    <!-- Skip to main content (a11y) -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-slate-900 focus:text-white focus:rounded-md focus:shadow-lg">
        Lewati ke konten utama
    </a>

    <!-- Top Navigation Header (Prototype Style: Deep Teal Bar) -->
    <header class="w-full bg-[#0d7a78] dark:bg-slate-800 text-white sticky top-0 z-30 shadow-md no-print transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & University Title -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-amber-400 text-teal-950 flex items-center justify-center font-black text-xl shadow-xs group-hover:scale-105 transition-transform" aria-hidden="true">
                            <svg class="w-6 h-6 text-teal-900" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm0 2.84L18 11v7h-2v-6H8v6H6v-7l6-5.16zM12 8a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"/>
                            </svg>
                        </div>
                        <span class="text-base sm:text-lg font-bold tracking-tight text-white">
                            Arsiparis Surat Digital
                        </span>
                    </a>

                    <!-- Top Horizontal Navigation Links (Desktop) -->
                    <nav class="hidden md:flex items-center gap-1 lg:gap-1.5" aria-label="Navigasi Utama">
                        <a
                            href="{{ route('dashboard') }}"
                            class="whitespace-nowrap inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#0a5c5a] dark:bg-slate-700 text-white shadow-inner' : 'text-teal-100 hover:bg-[#118b89] dark:hover:bg-slate-700 hover:text-white' }}"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Dropdown Menu: Surat -->
                        <div class="relative" x-data="{ open: false }">
                            <button
                                type="button"
                                @click="open = !open"
                                @click.away="open = false"
                                class="whitespace-nowrap inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('surat-masuk.*', 'surat-keluar.*', 'pengaturan.dokumen') ? 'bg-[#0a5c5a] dark:bg-slate-700 text-white shadow-inner' : 'text-teal-100 hover:bg-[#118b89] dark:hover:bg-slate-700 hover:text-white' }} cursor-pointer"
                            >
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>Surat</span>
                                <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                x-show="open"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                class="absolute left-0 mt-1.5 w-48 rounded-xl bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 shadow-xl border border-slate-200 dark:border-slate-700 py-1.5 z-50 text-xs font-semibold"
                                style="display: none;"
                            >
                                <a
                                    href="{{ route('surat-masuk.index') }}"
                                    class="flex items-center gap-2 px-3.5 py-2 hover:bg-teal-50 dark:hover:bg-slate-700 hover:text-[#0d7a78] dark:hover:text-teal-300 transition-colors {{ request()->routeIs('surat-masuk.*') ? 'bg-teal-50 dark:bg-slate-700 text-[#0d7a78] dark:text-teal-300 font-bold' : '' }}"
                                >
                                    <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <span>Surat Masuk</span>
                                </a>

                                <a
                                    href="{{ route('surat-keluar.index') }}"
                                    class="flex items-center gap-2 px-3.5 py-2 hover:bg-teal-50 dark:hover:bg-slate-700 hover:text-[#0d7a78] dark:hover:text-teal-300 transition-colors {{ request()->routeIs('surat-keluar.*') ? 'bg-teal-50 dark:bg-slate-700 text-[#0d7a78] dark:text-teal-300 font-bold' : '' }}"
                                >
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    <span>Surat Keluar</span>
                                </a>

                                <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>

                                <a
                                    href="{{ route('pengaturan.dokumen') }}"
                                    class="flex items-center gap-2 px-3.5 py-2 hover:bg-teal-50 dark:hover:bg-slate-700 hover:text-[#0d7a78] dark:hover:text-teal-300 transition-colors {{ request()->routeIs('pengaturan.dokumen') ? 'bg-teal-50 dark:bg-slate-700 text-[#0d7a78] dark:text-teal-300 font-bold' : '' }}"
                                >
                                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l.546.947c.276.479.17 1.096-.243 1.458l-1.002.881c-.27.238-.396.6-.328.95.007.037.014.075.02.112.068.35.258.653.585.81l1.134.542c.502.24.73.834.526 1.347l-.448.972a1.125 1.125 0 01-1.282.602l-1.258-.291a1.125 1.125 0 00-1.075.312c-.056.056-.113.11-.17.164a1.125 1.125 0 00-.328 1.002l.213 1.282c.09.542-.38.94-.93.94h-1.093c-.55 0-1.02-.398-1.11-.94l-.213-1.281a1.125 1.125 0 00-.645-.87c-.074-.04-.147-.083-.22-.127a1.125 1.125 0 00-1.075-.124l-1.217.456a1.125 1.125 0 01-1.37-.49l-.546-.947a1.125 1.125 0 012.243-1.458l1.002-.881c.27-.238.396-.6.328-.95a1.125 1.125 0 00-.02-.112 1.125 1.125 0 00-.585-.81l-1.134-.542a1.125 1.125 0 01-.526-1.347l.448-.972a1.125 1.125 0 011.282-.602l1.258.291c.394.091.808-.023 1.075-.312.056-.056.113-.11.17-.164a1.125 1.125 0 00.328-1.002l-.213-1.282z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Kop Surat</span>
                                </a>
                            </div>
                        </div>

                        <a
                            href="{{ route('arsip-digital.index') }}"
                            class="whitespace-nowrap inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('arsip-digital.*') ? 'bg-[#0a5c5a] dark:bg-slate-700 text-white shadow-inner' : 'text-teal-100 hover:bg-[#118b89] dark:hover:bg-slate-700 hover:text-white' }}"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <span>Arsip Digital</span>
                        </a>

                        <a
                            href="{{ route('laporan.index') }}"
                            class="whitespace-nowrap inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('laporan.*') ? 'bg-[#0a5c5a] dark:bg-slate-700 text-white shadow-inner' : 'text-teal-100 hover:bg-[#118b89] dark:hover:bg-slate-700 hover:text-white' }}"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Laporan</span>
                        </a>

                        <a
                            href="{{ route('data-master.index') }}"
                            class="whitespace-nowrap inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('data-master.*') ? 'bg-[#0a5c5a] dark:bg-slate-700 text-white shadow-inner' : 'text-teal-100 hover:bg-[#118b89] dark:hover:bg-slate-700 hover:text-white' }}"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7zM4 12h16" />
                            </svg>
                            <span>Data Master</span>
                        </a>
                    </nav>
                </div>

                <!-- User Profile, Theme Switcher & Actions Header -->
                <div class="flex items-center gap-2 sm:gap-2.5">
                    @auth
                        <!-- Theme Toggle Button (Light/Dark Mode) -->
                        <button
                            type="button"
                            @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                            title="Ganti Mode Tampilan (Terang/Gelap)"
                            class="p-2 rounded-xl bg-[#0a5c5a] dark:bg-slate-700 hover:bg-[#0e706e] dark:hover:bg-slate-600 text-teal-100 hover:text-white transition-colors cursor-pointer flex items-center justify-center min-h-9 min-w-9 active:scale-95"
                        >
                            <svg x-show="!darkMode" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                            <svg x-show="darkMode" x-cloak class="w-4 h-4 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21m8.966-8.966h-2.25M4.284 12h-2.25m15.364 6.364l-1.591-1.591M6.758 6.758L5.167 5.167m12.728 0l-1.591 1.591M6.758 17.242l-1.591 1.591M12 6.75a5.25 5.25 0 100 10.5 5.25 5.25 0 000-10.5z"/></svg>
                        </button>

                        <!-- In-App Notification Center Badge -->
                        @livewire('common.notification-badge')

                        <!-- User Pill Button -> Profile Link -->
                        <a
                            href="{{ route('profile.edit') }}"
                            title="Buka Profil Pengguna"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#0a5c5a] dark:bg-slate-700 hover:bg-[#0e706e] dark:hover:bg-slate-600 border border-teal-600/50 dark:border-slate-600 text-white text-xs font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white shrink-0 whitespace-nowrap cursor-pointer active:scale-95"
                        >
                            <span class="w-5 h-5 rounded-full bg-amber-400 text-teal-950 flex items-center justify-center font-bold text-[10px] shrink-0" aria-hidden="true">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="max-w-28 sm:max-w-36 truncate font-semibold">{{ auth()->user()->name }}</span>
                        </a>

                        <!-- Tombol Keluar dengan Crisp Exit Heroicon -->
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button
                                type="button"
                                onclick="confirmLogout(event)"
                                title="Keluar dari sistem"
                                class="p-2 rounded-xl bg-[#0a5c5a] dark:bg-slate-700 hover:bg-rose-700 dark:hover:bg-rose-600 text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white min-h-9 min-w-9 flex items-center justify-center cursor-pointer shrink-0 active:scale-95"
                            >
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Horizontal Subnav -->
        <div class="md:hidden border-t border-teal-700/60 bg-[#0c6e6c] dark:bg-slate-800 px-3 py-2 flex items-center gap-1.5 overflow-x-auto text-xs font-semibold scrollbar-none">
            <a href="{{ route('dashboard') }}" class="whitespace-nowrap px-2.5 py-1.5 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-[#084846] dark:bg-slate-700 text-white' : 'text-teal-100 hover:text-white' }}">Dashboard</a>
            <a href="{{ route('surat-masuk.index') }}" class="whitespace-nowrap px-2.5 py-1.5 rounded-lg transition {{ request()->routeIs('surat-masuk.*') ? 'bg-[#084846] dark:bg-slate-700 text-white' : 'text-teal-100 hover:text-white' }}">Surat Masuk</a>
            <a href="{{ route('surat-keluar.index') }}" class="whitespace-nowrap px-2.5 py-1.5 rounded-lg transition {{ request()->routeIs('surat-keluar.*') ? 'bg-[#084846] dark:bg-slate-700 text-white' : 'text-teal-100 hover:text-white' }}">Surat Keluar</a>
            <a href="{{ route('arsip-digital.index') }}" class="whitespace-nowrap px-2.5 py-1.5 rounded-lg transition {{ request()->routeIs('arsip-digital.*') ? 'bg-[#084846] dark:bg-slate-700 text-white' : 'text-teal-100 hover:text-white' }}">Arsip Digital</a>
            <a href="{{ route('laporan.index') }}" class="whitespace-nowrap px-2.5 py-1.5 rounded-lg transition {{ request()->routeIs('laporan.*') ? 'bg-[#084846] dark:bg-slate-700 text-white' : 'text-teal-100 hover:text-white' }}">Laporan</a>
            <a href="{{ route('data-master.index') }}" class="whitespace-nowrap px-2.5 py-1.5 rounded-lg transition {{ request()->routeIs('data-master.*') ? 'bg-[#084846] dark:bg-slate-700 text-white' : 'text-teal-100 hover:text-white' }}">Data Master</a>
        </div>
    </header>

    <!-- Main Container (Full-Width Clean Layout) -->
    <div class="grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Main Content Area -->
        <main id="main-content" class="min-w-0">
            <!-- Content Slot -->
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    <!-- University Footer -->
    <footer class="w-full py-4 px-4 text-center border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs text-slate-500 dark:text-slate-400 no-print mt-auto transition-colors duration-200">
        <p>&copy; {{ date('Y') }} {{ config('university.name', 'Universitas Digital Nusantara') }}. Sistem Tata Usaha &amp; Kearsipan Surat Terpusat.</p>
    </footer>

    <!-- SweetAlert2 Sweet Toast System -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function isDarkTheme() {
            return document.documentElement.classList.contains('dark');
        }

        const CustomToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-xl shadow-xl border text-xs font-semibold'
            },
            didOpen: (toast) => {
                if (isDarkTheme()) {
                    toast.style.backgroundColor = '#1e293b';
                    toast.style.color = '#f8fafc';
                    toast.style.borderColor = '#334155';
                } else {
                    toast.style.backgroundColor = '#ffffff';
                    toast.style.color = '#0f172a';
                    toast.style.borderColor = '#e2e8f0';
                }
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('status'))
                CustomToast.fire({
                    icon: 'success',
                    title: "{{ session('status') }}"
                });
            @elseif (session('success'))
                CustomToast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @elseif (session('error'))
                CustomToast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-toast', (data) => {
                CustomToast.fire({
                    icon: data.type || 'info',
                    title: data.message || 'Pemberitahuan Sistem'
                });
            });
        });

        function confirmLogout(event) {
            event.preventDefault();
            const dark = isDarkTheme();
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin mengakhiri sesi kedinasan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d7a78',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                background: dark ? '#1e293b' : '#ffffff',
                color: dark ? '#f8fafc' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl border shadow-2xl text-xs font-sans ' + (dark ? 'border-slate-700' : 'border-slate-200'),
                    confirmButton: 'px-4 py-2 text-xs font-bold rounded-xl text-white shadow-xs',
                    cancelButton: 'px-4 py-2 text-xs font-bold rounded-xl text-white shadow-xs'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    @livewireScripts
</body>
</html>
