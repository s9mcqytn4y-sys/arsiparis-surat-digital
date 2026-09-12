@extends('layouts.auth')

@section('title', 'Masuk ke Portal')

@section('content')
<div class="w-full overflow-hidden rounded-3xl bg-white shadow-2xl border border-teal-700/20">
    <div class="grid grid-cols-1 md:grid-cols-2 min-h-[460px]">
        <!-- Kolom Kiri: Identitas Brand (Presisi Prototype Gambar 1) -->
        <div class="relative flex flex-col items-center justify-center p-8 sm:p-12 bg-[#0b6361] text-white text-center">
            <div class="flex flex-col items-center gap-4">
                <!-- Icon Box House/Building -->
                <div class="w-16 h-16 rounded-2xl bg-teal-800/50 border border-teal-500/30 text-amber-300 flex items-center justify-center shadow-inner">
                    <svg class="w-9 h-9 text-amber-300" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm0 2.84L18 11v7h-2v-6H8v6H6v-7l6-5.16zM12 8a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                        Arsiparis Surat Digital
                    </h1>
                    <p class="text-xs sm:text-sm text-teal-100/90 font-medium mt-1">
                        {{ config('university.name', 'Universitas Digital Nusantara') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Formulir Masuk Minimalis (Presisi Prototype Gambar 1) -->
        <div class="flex flex-col justify-center p-8 sm:p-12 bg-white">
            <div class="mb-6">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Masuk ke Portal
                </h2>
                <p class="mt-1 text-xs text-slate-500 font-medium">
                    Masukkan identitas akun dan kata sandi Anda.
                </p>
            </div>

            <!-- Notifikasi Status Sesi -->
            @if (session('status'))
                <div class="mb-5 flex items-center gap-2 rounded-xl bg-teal-50 p-3.5 border border-teal-200 text-xs font-medium text-teal-800" role="status">
                    <svg class="w-4 h-4 shrink-0 text-[#0d7a78]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4" novalidate>
                @csrf

                <!-- Username, Surel, atau NIP -->
                <div>
                    <label for="identity" class="block text-xs font-semibold text-slate-700 mb-1">
                        Username, Surel, atau NIP
                    </label>
                    <input
                        id="identity"
                        name="identity"
                        type="text"
                        autocomplete="off"
                        autofocus
                        required
                        value="{{ old('identity', 'admin') }}"
                        class="w-full px-3.5 py-2.5 text-sm rounded-xl border text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 transition-colors {{ $errors->has('identity') ? 'border-rose-500 bg-rose-50/20 focus:ring-rose-500' : 'border-slate-300 focus:border-[#0d7a78] focus:ring-[#0d7a78]' }}"
                        placeholder="Masukkan username, surel, atau NIP"
                    >
                    @error('identity')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-600 font-medium" id="identity-error" role="alert">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Kata Sandi dengan Eye Toggle -->
                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            :type="showPassword ? 'text' : 'password'"
                            autocomplete="off"
                            required
                            class="w-full px-3.5 py-2.5 text-sm rounded-xl border text-slate-900 pr-10 placeholder:text-slate-400 focus:outline-none focus:ring-1 transition-colors {{ $errors->has('password') ? 'border-rose-500 bg-rose-50/20 focus:ring-rose-500' : 'border-slate-300 focus:border-[#0d7a78] focus:ring-[#0d7a78]' }}"
                            placeholder="••••••••"
                        >
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            :title="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                        >
                            <span x-show="!showPassword" class="flex items-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </span>
                            <span x-show="showPassword" class="flex items-center" style="display: none;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a9.957 9.957 0 014.122-.963c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18" />
                                </svg>
                            </span>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-600 font-medium" id="password-error" role="alert">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center pt-1">
                    <label for="remember" class="inline-flex items-center cursor-pointer select-none">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-slate-300 text-[#0d7a78] focus:ring-[#0d7a78] cursor-pointer"
                        >
                        <span class="ml-2 text-xs text-slate-600 font-medium">
                            Ingat sesi saya
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-[#0d7a78] hover:bg-[#0a5c5a] focus:outline-none focus:ring-2 focus:ring-[#0d7a78] focus:ring-offset-2 transition-all cursor-pointer shadow-xs active:scale-95"
                    >
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Swal) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Identitas akun atau kata sandi tidak sesuai.',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        }
    });
</script>
@endif
@endsection
