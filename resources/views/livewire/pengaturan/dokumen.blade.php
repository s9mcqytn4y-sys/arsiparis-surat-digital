<div>
    <!-- Header Pengaturan Templat Dokumen -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Pengaturan Kop Surat & Templat Dokumen</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
            Konfigurasi identitas perguruan tinggi, logo resmi, dan alamat untuk cetakan surat & laporan kearsipan.
        </p>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="mb-6 flex items-center justify-between rounded-xl bg-emerald-50 dark:bg-emerald-950/40 p-4 border border-emerald-200 dark:border-emerald-800 text-xs font-medium text-emerald-800 dark:text-emerald-300" role="status">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Form Pengaturan -->
        <div class="lg:col-span-8 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs p-6 transition-colors">
            <form wire:submit.prevent="simpan" class="space-y-6" novalidate>
                <!-- Section 1: Identitas Institusi -->
                <div class="space-y-4">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-700 pb-2">
                        Identitas Perguruan Tinggi & Kop Resmi
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama_institusi" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Nama Perguruan Tinggi <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="nama_institusi"
                                type="text"
                                wire:model="nama_institusi"
                                class="w-full px-3.5 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-1 dark:bg-slate-900 dark:text-slate-100 border-slate-300 dark:border-slate-700 focus:border-[#0d7a78]"
                            >
                        </div>

                        <div>
                            <label for="nama_fakultas" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Nama Fakultas / Unit Utama
                            </label>
                            <input
                                id="nama_fakultas"
                                type="text"
                                wire:model="nama_fakultas"
                                placeholder="Contoh: Fakultas Teknologi Informasi dan Komunikasi"
                                class="w-full px-3.5 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-1 dark:bg-slate-900 dark:text-slate-100 border-slate-300 dark:border-slate-700 focus:border-[#0d7a78]"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="alamat_lengkap" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Alamat Lengkap Kampus <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            id="alamat_lengkap"
                            rows="2"
                            wire:model="alamat_lengkap"
                            class="w-full px-3.5 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-1 dark:bg-slate-900 dark:text-slate-100 border-slate-300 dark:border-slate-700 focus:border-[#0d7a78]"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="telepon" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Telepon
                            </label>
                            <input
                                id="telepon"
                                type="text"
                                wire:model="telepon"
                                placeholder="(024) 8412345"
                                class="w-full px-3.5 py-2 text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#0d7a78]"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Email Resmi
                            </label>
                            <input
                                id="email"
                                type="email"
                                wire:model="email"
                                placeholder="info@universitas.ac.id"
                                class="w-full px-3.5 py-2 text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#0d7a78]"
                            >
                        </div>

                        <div>
                            <label for="website" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Website
                            </label>
                            <input
                                id="website"
                                type="text"
                                wire:model="website"
                                placeholder="www.universitas.ac.id"
                                class="w-full px-3.5 py-2 text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#0d7a78]"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="kota_penerbitan" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Kota Penerbitan Surat <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="kota_penerbitan"
                            type="text"
                            wire:model="kota_penerbitan"
                            placeholder="Semarang"
                            class="w-full px-3.5 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-1 dark:bg-slate-900 dark:text-slate-100 border-slate-300 dark:border-slate-700 focus:border-[#0d7a78]"
                        >
                    </div>
                </div>

                <!-- Section 2: Logo Institusi -->
                <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100">Logo Perguruan Tinggi</h2>

                    <div class="flex items-center gap-4">
                        @if ($logo)
                            <div class="w-16 h-16 rounded-xl border border-slate-200 dark:border-slate-700 p-1 flex items-center justify-center bg-slate-50 dark:bg-slate-900 shrink-0">
                                <img src="{{ $logo->temporaryUrl() }}" alt="Preview Logo" class="max-h-full max-w-full object-contain">
                            </div>
                        @elseif ($existing_logo_path && Storage::disk('public')->exists($existing_logo_path))
                            <div class="w-16 h-16 rounded-xl border border-slate-200 dark:border-slate-700 p-1 flex items-center justify-center bg-slate-50 dark:bg-slate-900 shrink-0">
                                <img src="{{ Storage::url($existing_logo_path) }}" alt="Logo Saat Ini" class="max-h-full max-w-full object-contain">
                            </div>
                        @else
                            <div class="w-16 h-16 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex items-center justify-center bg-slate-50 dark:bg-slate-900 text-slate-400 shrink-0">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </div>
                        @endif

                        <div class="grow">
                            <input
                                type="file"
                                wire:model="logo"
                                accept="image/png,image/jpeg,image/svg+xml"
                                class="text-xs text-slate-700 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-slate-700 file:text-[#0d7a78] dark:file:text-teal-300 hover:file:bg-teal-100 cursor-pointer"
                            >
                        </div>
                    </div>
                </div>

                <!-- Section 3: Catatan Kaki (Footer) -->
                <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <div>
                        <label for="catatan_footer" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Catatan Kaki (Footer) Laporan & Cetakan
                        </label>
                        <textarea
                            id="catatan_footer"
                            rows="2"
                            wire:model="catatan_footer"
                            placeholder="Catatan kaki resmi pada cetakan surat & laporan..."
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#0d7a78]"
                        ></textarea>
                    </div>
                </div>

                <!-- Footer Form Button -->
                <div class="flex items-center justify-end pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-5 py-2.5 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold shadow-xs transition cursor-pointer flex items-center gap-2 active:scale-95"
                    >
                        <span>Simpan Pengaturan Templat</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Live Preview Kop Surat -->
        <div class="lg:col-span-4">
            <div class="sticky top-6 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs p-5 transition-colors">
                <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 dark:border-slate-700 pb-2">
                    Pratinjau Kop Surat Cetak
                </h3>

                <div class="bg-slate-50 dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-700 font-serif text-slate-800 dark:text-slate-200 text-center text-xs space-y-1">
                    <div class="flex items-center justify-center gap-3 border-b-2 border-slate-900 dark:border-slate-100 pb-3 mb-2">
                        @if ($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="Logo" class="w-10 h-10 object-contain shrink-0">
                        @elseif ($existing_logo_path && Storage::disk('public')->exists($existing_logo_path))
                            <img src="{{ Storage::url($existing_logo_path) }}" alt="Logo" class="w-10 h-10 object-contain shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-full bg-[#0d7a78] text-white flex items-center justify-center font-sans font-bold text-xs shrink-0">
                                U
                            </div>
                        @endif

                        <div class="font-sans text-left">
                            <h4 class="font-bold text-slate-900 dark:text-slate-100 uppercase text-xs tracking-tight">
                                {{ $nama_institusi ?: AppConstants::INSTITUSI_DEFAULT }}
                            </h4>
                            @if ($nama_fakultas)
                                <p class="text-[11px] font-medium text-slate-700 dark:text-slate-300">
                                    {{ $nama_fakultas }}
                                </p>
                            @endif
                            <p class="text-[9px] text-slate-500 dark:text-slate-400 font-normal leading-tight mt-0.5">
                                {{ $alamat_lengkap }}
                                @if ($telepon) | Telp: {{ $telepon }} @endif
                                @if ($email) | Email: {{ $email }} @endif
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 font-sans text-right text-[11px]">
                        <p class="text-slate-600 dark:text-slate-400">{{ $kota_penerbitan }}, {{ date('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
