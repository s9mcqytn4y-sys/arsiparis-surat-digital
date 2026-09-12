@extends('layouts.auth')

@section('title', 'Masuk Portal Naskah Dinas')

@section('content')
<div class="bg-white py-8 px-6 shadow-sm border border-slate-200 rounded-lg sm:px-10">
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold tracking-tight text-slate-900">
            {{ __('persuratan.auth.login_title') }}
        </h2>
        <p class="mt-1 text-xs text-slate-500 font-medium">
            {{ __('persuratan.auth.login_subtitle') }}
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-md" role="status">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-md" role="alert" id="form-error-alert">
            <p class="font-semibold mb-1">Terdapat kendala saat memproses permohonan:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="space-y-5" novalidate>
        @csrf

        <!-- Input Identitas (NIP / Email) -->
        <div>
            <label for="identity" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                {{ __('persuratan.auth.nip_or_email') }} <span class="text-rose-500" aria-hidden="true">*</span>
            </label>
            <div class="relative">
                <input 
                    id="identity" 
                    name="identity" 
                    type="text" 
                    inputmode="email"
                    autocomplete="username" 
                    required 
                    value="{{ old('identity') }}"
                    aria-required="true"
                    aria-invalid="{{ $errors->has('identity') ? 'true' : 'false' }}"
                    @if($errors->has('identity')) aria-describedby="identity-error" @endif
                    class="block w-full rounded-md border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset {{ $errors->has('identity') ? 'ring-rose-400 focus:ring-rose-600' : 'ring-slate-300 focus:ring-slate-800' }} placeholder:text-slate-400 text-sm leading-6 focus:ring-2 focus:ring-inset"
                    placeholder="Contoh: 19850101... atau nama@universitas.ac.id"
                >
            </div>
            @error('identity')
                <p class="mt-1.5 text-xs text-rose-600 font-medium" id="identity-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input Kata Sandi -->
        <div x-data="{ showPassword: false }">
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                    {{ __('persuratan.auth.password') }} <span class="text-rose-500" aria-hidden="true">*</span>
                </label>
            </div>
            <div class="relative">
                <input 
                    id="password" 
                    name="password" 
                    :type="showPassword ? 'text' : 'password'"
                    autocomplete="current-password" 
                    required 
                    aria-required="true"
                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                    @if($errors->has('password')) aria-describedby="password-error" @endif
                    class="block w-full rounded-md border-0 py-2.5 px-3 pr-10 text-slate-900 shadow-sm ring-1 ring-inset {{ $errors->has('password') ? 'ring-rose-400 focus:ring-rose-600' : 'ring-slate-300 focus:ring-slate-800' }} placeholder:text-slate-400 text-sm leading-6 focus:ring-2 focus:ring-inset"
                    placeholder="••••••••••••"
                >
                <button 
                    type="button" 
                    @click="showPassword = !showPassword" 
                    :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-medium text-slate-500 hover:text-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-800 rounded min-w-[44px] justify-center"
                >
                    <span x-text="showPassword ? 'Tutup' : 'Lihat'">Lihat</span>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-rose-600 font-medium" id="password-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Ingat Sesi -->
        <div class="flex items-center justify-between">
            <div class="flex items-center h-5">
                <input 
                    id="remember" 
                    name="remember" 
                    type="checkbox" 
                    class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-800 focus:ring-offset-0 cursor-pointer"
                >
                <label for="remember" class="ml-2 block text-xs text-slate-600 font-medium cursor-pointer">
                    {{ __('persuratan.auth.remember_me') }}
                </label>
            </div>
        </div>

        <!-- Tombol Kirim Form -->
        <div>
            <button 
                type="submit" 
                class="w-full min-h-[44px] flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900 transition-colors duration-150"
            >
                {{ __('persuratan.auth.submit_login') }}
            </button>
        </div>
    </form>

    <!-- Pemberitahuan Protokol Keamanan Zero Trust -->
    <div class="mt-6 pt-4 border-t border-slate-100 flex items-start gap-2 text-slate-500 text-[11px] leading-tight">
        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <span>{{ __('persuratan.auth.protected_notice') }}</span>
    </div>
</div>
@endsection
