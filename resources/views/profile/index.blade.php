@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="{
    avatarPreview: '{{ $user->avatar_url ?? '' }}',
    handleAvatarChange(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.size > 2097152) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran Berkas Terlalu Besar',
                        text: 'Ukuran foto avatar maksimal 2MB.',
                        confirmButtonColor: '#0d7a78'
                    });
                } else {
                    alert('Ukuran foto avatar maksimal 2MB.');
                }
                event.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = (e) => {
                this.avatarPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}">
    <!-- Header -->
    <div class="border-b border-slate-200 dark:border-slate-700 pb-4 transition-colors">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">Profil Pengguna Kedinasan</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola identitas kedinasan, foto profil persuratan, dan keamanan akun Anda.</p>
    </div>

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800/60 bg-emerald-50 dark:bg-emerald-950/40 p-4 text-xs font-semibold text-emerald-800 dark:text-emerald-300 flex items-center gap-3">
            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Layout 2 Kolom Interaktif Desktop -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        <!-- KOLOM KIRI: Biodata Kedinasan & Avatar Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800 p-6 shadow-xs text-center transition-colors">
                <!-- Avatar Section -->
                <div class="relative mx-auto size-28 rounded-full border-4 border-teal-50 dark:border-slate-700 bg-slate-100 dark:bg-slate-900 flex items-center justify-center overflow-hidden shadow-xs">
                    <template x-if="avatarPreview">
                        <img :src="avatarPreview" alt="Foto Profil" class="size-full object-cover" />
                    </template>
                    <template x-if="!avatarPreview">
                        <div class="size-full bg-[#0d7a78] text-white flex items-center justify-center font-bold text-3xl">
                            {{ $user->initials }}
                        </div>
                    </template>
                </div>

                <div class="mt-4">
                    <label for="avatar_input" class="inline-flex items-center gap-2 cursor-pointer rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-xs hover:bg-slate-50 dark:hover:bg-slate-600 transition active:scale-95">
                        <svg class="size-4 text-slate-500 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                        <span>Ganti Foto Avatar</span>
                        <input
                            type="file"
                            id="avatar_input"
                            name="avatar"
                            form="profile-form"
                            accept="image/png, image/jpeg, image/webp"
                            class="sr-only"
                            @change="handleAvatarChange($event)"
                        />
                    </label>
                    <p class="text-[11px] text-slate-400 dark:text-slate-400 mt-1.5">PNG, JPG, atau WEBP (Maks. 2MB)</p>
                    @error('avatar')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5 border-t border-slate-100 dark:border-slate-700 pt-4 text-left space-y-3">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Nama Akun</span>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $user->name }}</p>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Hak Akses / Role</span>
                        <div class="mt-1 flex flex-wrap gap-1">
                            @forelse ($user->roles as $role)
                                <span class="inline-flex items-center rounded-md bg-teal-50 dark:bg-slate-700 px-2.5 py-1 text-xs font-semibold text-teal-800 dark:text-teal-300 border border-teal-200 dark:border-slate-600">
                                    {{ strtoupper(str_replace('_', ' ', $role->name)) }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-500">Pengguna Terdaftar</span>
                            @endforelse
                        </div>
                    </div>
                    @if ($pegawai)
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">NIP / NIDN</span>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300 font-mono">{{ $pegawai->nip_nidn ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Jabatan Kedinasan</span>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $pegawai->jabatan ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Unit Kerja</span>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $pegawai->unitKerja->nama_unit ?? '-' }}</p>
                        </div>
                    @endif
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Status Akun</span>
                        <div class="mt-0.5 flex items-center gap-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400">
                            <span class="size-2 rounded-full bg-emerald-500"></span>
                            Aktif & Terverifikasi
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Form Informasi Akun & Form Perbarui Kata Sandi -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Card Form Informasi Akun -->
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800 p-6 shadow-xs transition-colors">
                <div class="border-b border-slate-100 dark:border-slate-700 pb-3 mb-5">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Informasi Akun</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbarui nama tampilan dan surel resmi instansi Anda.</p>
                </div>

                <form id="profile-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap *</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            aria-required="true"
                            class="w-full rounded-xl border dark:bg-slate-900 dark:text-slate-100 {{ $errors->has('name') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-slate-300 dark:border-slate-700' }} px-3.5 py-2.5 text-sm focus:border-[#0d7a78] focus:ring-1 focus:ring-[#0d7a78] focus:outline-none"
                        />
                        @error('name')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Alamat Surel Resmi *</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            aria-required="true"
                            class="w-full rounded-xl border dark:bg-slate-900 dark:text-slate-100 {{ $errors->has('email') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-slate-300 dark:border-slate-700' }} px-3.5 py-2.5 text-sm focus:border-[#0d7a78] focus:ring-1 focus:ring-[#0d7a78] focus:outline-none"
                        />
                        @error('email')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button
                            type="submit"
                            class="rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] px-5 py-2.5 text-xs font-semibold text-white shadow-xs transition active:scale-95"
                        >
                            Simpan Perubahan Akun
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Form Perbarui Kata Sandi -->
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800 p-6 shadow-xs transition-colors" x-data="{ showCurrent: false, showNew: false }">
                <div class="border-b border-slate-100 dark:border-slate-700 pb-3 mb-5">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Perbarui Kata Sandi</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pastikan akun Anda menggunakan kata sandi yang aman dan tidak dibagikan.</p>
                </div>

                <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Kata Sandi Saat Ini *</label>
                        <div class="relative">
                            <input
                                :type="showCurrent ? 'text' : 'password'"
                                id="current_password"
                                name="current_password"
                                required
                                aria-required="true"
                                autocomplete="current-password"
                                class="w-full rounded-xl border dark:bg-slate-900 dark:text-slate-100 {{ $errors->has('current_password') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-slate-300 dark:border-slate-700' }} px-3.5 py-2.5 pr-10 text-sm focus:border-[#0d7a78] focus:ring-1 focus:ring-[#0d7a78] focus:outline-none"
                            />
                            <button
                                type="button"
                                @click="showCurrent = !showCurrent"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                aria-label="Tampilkan atau sembunyikan kata sandi saat ini"
                            >
                                <svg x-show="!showCurrent" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <svg x-show="showCurrent" x-cloak class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Kata Sandi Baru *</label>
                        <div class="relative">
                            <input
                                :type="showNew ? 'text' : 'password'"
                                id="password"
                                name="password"
                                required
                                aria-required="true"
                                autocomplete="new-password"
                                class="w-full rounded-xl border dark:bg-slate-900 dark:text-slate-100 {{ $errors->has('password') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-slate-300 dark:border-slate-700' }} px-3.5 py-2.5 pr-10 text-sm focus:border-[#0d7a78] focus:ring-1 focus:ring-[#0d7a78] focus:outline-none"
                            />
                            <button
                                type="button"
                                @click="showNew = !showNew"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                aria-label="Tampilkan atau sembunyikan kata sandi baru"
                            >
                                <svg x-show="!showNew" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <svg x-show="showNew" x-cloak class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Kata Sandi Baru *</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            aria-required="true"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 px-3.5 py-2.5 text-sm focus:border-[#0d7a78] focus:ring-1 focus:ring-[#0d7a78] focus:outline-none"
                        />
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button
                            type="submit"
                            class="rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] px-5 py-2.5 text-xs font-semibold text-white shadow-xs transition active:scale-95"
                        >
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if (session('status'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Swal) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('status') }}",
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        }
    });
</script>
@endif
@endsection
