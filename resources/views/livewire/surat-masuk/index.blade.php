<div class="space-y-6">
    <!-- Header Halaman & Tombol Tambah -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Manajemen Surat Masuk</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pencatatan agenda naskah dinas masuk terpusat perguruan tinggi</p>
        </div>
        <div>
            <button
                type="button"
                wire:click="bukaTambahModal"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0d7a78] hover:bg-[#0b6462] text-white text-xs font-semibold shadow-xs transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0d7a78] cursor-pointer active:scale-95"
            >
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Surat Masuk</span>
            </button>
        </div>
    </div>

    <!-- Panel Pencarian & Filter -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-2xs transition-colors">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <!-- Search Text Input -->
            <div class="md:col-span-6">
                <label for="search-input" class="sr-only">Cari naskah dinas</label>
                <div class="relative">
                    <input
                        id="search-input"
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari nomor surat, pengirim, atau perihal..."
                        class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:border-[#0d7a78] focus:ring-1 focus:ring-[#0d7a78] transition-colors"
                    />
                </div>
            </div>

            <!-- Date Filter Input -->
            <div class="md:col-span-4">
                <label for="filter-tanggal" class="sr-only">Filter tanggal surat</label>
                <input
                    id="filter-tanggal"
                    type="date"
                    wire:model.live="filterTanggal"
                    class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#0d7a78] focus:ring-1 focus:ring-[#0d7a78] transition-colors"
                />
            </div>

            <!-- Reset Button -->
            <div class="md:col-span-2">
                <button
                    type="button"
                    wire:click="resetFilter"
                    class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl bg-[#1e293b] hover:bg-slate-800 text-white text-xs font-semibold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-700 cursor-pointer active:scale-95"
                >
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Tabel Data Surat Masuk -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-2xs overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <!-- Header Deep Teal #0d7a78 -->
                <thead>
                    <tr class="bg-[#0d7a78] dark:bg-slate-700 text-white font-semibold text-xs tracking-wider">
                        <th scope="col" class="py-3.5 px-4 w-12 text-center">No</th>
                        <th scope="col" class="py-3.5 px-4 w-44">Nomor Surat</th>
                        <th scope="col" class="py-3.5 px-4 w-32">Tanggal</th>
                        <th scope="col" class="py-3.5 px-4 w-48">Pengirim</th>
                        <th scope="col" class="py-3.5 px-4">Perihal</th>
                        <th scope="col" class="py-3.5 px-4 w-52">File</th>
                        <th scope="col" class="py-3.5 px-4 w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-200">
                    @forelse($daftarSurat as $index => $surat)
                        <tr class="hover:bg-teal-50/40 dark:hover:bg-slate-700/50 transition-colors">
                            <!-- No -->
                            <td class="py-3 px-4 text-center text-xs font-medium text-slate-500 dark:text-slate-400">
                                {{ $daftarSurat->firstItem() + $index }}
                            </td>

                            <!-- Nomor Surat -->
                            <td class="py-3 px-4 font-semibold text-slate-900 dark:text-slate-100 text-xs">
                                <div>{{ $surat->nomor_surat }}</div>
                                <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono font-normal">{{ $surat->nomor_agenda }}</div>
                            </td>

                            <!-- Tanggal -->
                            <td class="py-3 px-4 text-xs whitespace-nowrap text-slate-600 dark:text-slate-300">
                                {{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-' }}
                            </td>

                            <!-- Pengirim -->
                            <td class="py-3 px-4 text-xs font-medium text-slate-800 dark:text-slate-200">
                                {{ $surat->pengirim }}
                            </td>

                            <!-- Perihal -->
                            <td class="py-3 px-4 text-xs text-slate-600 dark:text-slate-300">
                                <p class="line-clamp-2">{{ $surat->perihal }}</p>
                            </td>

                            <!-- File (Crisp Vector PDF Icon) -->
                            <td class="py-3 px-4 text-xs">
                                @if($surat->file_path)
                                    <button
                                        type="button"
                                        wire:click="bukaPreview('{{ $surat->id }}')"
                                        title="Pratinjau naskah PDF"
                                        class="inline-flex items-center gap-1.5 text-slate-700 dark:text-slate-200 hover:text-[#0d7a78] font-medium transition-colors text-left group cursor-pointer"
                                    >
                                        <!-- Crisp Vector PDF Icon -->
                                        <svg class="w-4 h-4 shrink-0 text-rose-600 dark:text-rose-500 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        <span class="truncate max-w-40 underline decoration-slate-300 dark:decoration-slate-600 group-hover:decoration-[#0d7a78]">
                                            {{ basename($surat->file_path) }}
                                        </span>
                                    </button>
                                @else
                                    <span class="text-slate-400 text-xs italic">Tidak ada berkas</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center gap-1.5">
                                    <!-- Unduh Berkas -->
                                    @if($surat->file_path)
                                        <a
                                            href="{{ route('documents.download', ['document' => $surat->id]) }}"
                                            title="Unduh Berkas Naskah"
                                            class="w-7 h-7 rounded-lg bg-[#0d7a78] hover:bg-[#0a5c5a] text-white flex items-center justify-center transition-transform hover:scale-105 cursor-pointer"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    @endif

                                    <!-- Edit Surat Masuk -->
                                    <button
                                        type="button"
                                        wire:click="bukaEditModal('{{ $surat->id }}')"
                                        title="Ubah Data Naskah"
                                        class="w-7 h-7 rounded-lg bg-sky-600 hover:bg-sky-700 text-white flex items-center justify-center transition-transform hover:scale-105 cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <!-- Hapus Surat Masuk (SweetAlert2 Konfirmasi) -->
                                    <button
                                        type="button"
                                        onclick="confirmDelete('{{ $surat->id }}', '{{ addslashes($surat->nomor_surat) }}')"
                                        title="Hapus Naskah Dinas"
                                        class="w-7 h-7 rounded-lg bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center transition-transform hover:scale-105 cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-sm">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="font-medium text-slate-500 dark:text-slate-400">Belum ada data surat masuk</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">Silakan klik tombol "+ Tambah Surat Masuk" untuk mencatat naskah baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarSurat->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                {{ $daftarSurat->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL FORM TAMBAH / EDIT SURAT MASUK -->
    <x-modal
        :show="$showFormModal"
        :title="$isEditing ? 'Ubah Data Surat Masuk' : 'Tambah Surat Masuk'"
        onClose="tutupModal"
        maxWidth="lg"
    >
        <form id="form-surat-masuk" wire:submit.prevent="simpan" class="space-y-4">
            <!-- Nomor Surat -->
            <div>
                <label for="form-nomor-surat" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                    Nomor Surat <span class="text-rose-500">*</span>
                </label>
                <input
                    id="form-nomor-surat"
                    type="text"
                    wire:model="nomor_surat"
                    autofocus
                    placeholder="Contoh: 123/UN.1/TU/2026"
                    class="w-full px-3.5 py-2 text-sm border rounded-xl transition-colors focus:outline-none focus:ring-1 dark:bg-slate-900 dark:text-slate-100 {{ $errors->has('nomor_surat') ? 'border-rose-500 bg-rose-50/20 focus:ring-rose-500' : 'border-slate-200 dark:border-slate-700 focus:border-[#0d7a78] focus:ring-[#0d7a78]' }}"
                />
                @error('nomor_surat')
                    <p class="text-rose-600 text-xs mt-1 flex items-center gap-1" role="alert">
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Tanggal -->
            <div>
                <label for="form-tanggal-surat" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                    Tanggal <span class="text-rose-500">*</span>
                </label>
                <input
                    id="form-tanggal-surat"
                    type="date"
                    wire:model="tanggal_surat"
                    class="w-full px-3.5 py-2 text-sm border rounded-xl transition-colors focus:outline-none focus:ring-1 dark:bg-slate-900 dark:text-slate-100 {{ $errors->has('tanggal_surat') ? 'border-rose-500 bg-rose-50/20 focus:ring-rose-500' : 'border-slate-200 dark:border-slate-700 focus:border-[#0d7a78] focus:ring-[#0d7a78]' }}"
                />
                @error('tanggal_surat')
                    <p class="text-rose-600 text-xs mt-1 flex items-center gap-1" role="alert">
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Pengirim -->
            <div>
                <label for="form-pengirim" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                    Pengirim <span class="text-rose-500">*</span>
                </label>
                <select
                    id="form-pengirim"
                    wire:model.live="pengirim"
                    class="w-full px-3.5 py-2 text-sm border rounded-xl transition-colors focus:outline-none focus:ring-1 bg-white dark:bg-slate-900 dark:text-slate-100 {{ $errors->has('pengirim') ? 'border-rose-500 bg-rose-50/20 focus:ring-rose-500' : 'border-slate-200 dark:border-slate-700 focus:border-[#0d7a78] focus:ring-[#0d7a78]' }}"
                >
                    <option value="">-- Pilih Asal Pengirim Surat --</option>
                    @foreach ($daftarPengirim as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                    @endforeach
                    <option value="__custom__">+ Input Manual / Tambah Baru</option>
                </select>

                @if ($pengirim === '__custom__' || $isCustomPengirim)
                    <div class="mt-2">
                        <input
                            type="text"
                            wire:model="pengirim_manual"
                            placeholder="Masukkan nama pengirim secara manual..."
                            class="w-full px-3.5 py-2 text-sm border rounded-xl transition-colors focus:outline-none focus:ring-1 dark:bg-slate-900 dark:text-slate-100 {{ $errors->has('pengirim_manual') ? 'border-rose-500 bg-rose-50/20 focus:ring-rose-500' : 'border-slate-200 dark:border-slate-700 focus:border-[#0d7a78] focus:ring-[#0d7a78]' }}"
                        />
                        @error('pengirim_manual')
                            <p class="text-rose-600 text-xs mt-1 flex items-center gap-1" role="alert">
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                @endif
            </div>

            <!-- Perihal -->
            <div>
                <label for="form-perihal" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                    Perihal <span class="text-rose-500">*</span>
                </label>
                <textarea
                    id="form-perihal"
                    wire:model="perihal"
                    rows="3"
                    placeholder="Tuliskan pokok perihal naskah masuk..."
                    class="w-full px-3.5 py-2 text-sm border rounded-xl transition-colors focus:outline-none focus:ring-1 dark:bg-slate-900 dark:text-slate-100 {{ $errors->has('perihal') ? 'border-rose-500 bg-rose-50/20 focus:ring-rose-500' : 'border-slate-200 dark:border-slate-700 focus:border-[#0d7a78] focus:ring-[#0d7a78]' }}"
                ></textarea>
                @error('perihal')
                    <p class="text-rose-600 text-xs mt-1 flex items-center gap-1" role="alert">
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Berkas Lampiran PDF -->
            <div>
                <label for="form-file-surat" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                    Berkas Lampiran (PDF)
                </label>
                <input
                    id="form-file-surat"
                    type="file"
                    wire:model="file_surat"
                    accept="application/pdf"
                    class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-slate-700 file:text-[#0d7a78] dark:file:text-teal-300 hover:file:bg-teal-100 cursor-pointer"
                />
            </div>
        </form>

        <x-slot:footer>
            <div class="flex items-center justify-end gap-2.5">
                <button
                    type="button"
                    wire:click="tutupModal"
                    class="px-4 py-2 rounded-xl bg-[#1e293b] hover:bg-slate-800 text-white text-xs font-semibold transition cursor-pointer active:scale-95"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    form="form-surat-masuk"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-[#0d7a78] hover:bg-[#0b6462] text-white text-xs font-semibold shadow-xs transition cursor-pointer active:scale-95"
                >
                    <span>{{ $isEditing ? 'Perbarui Surat' : 'Simpan Surat' }}</span>
                </button>
            </div>
        </x-slot:footer>
    </x-modal>

    <!-- MODAL PRATINJAU PDF INLINE -->
    <x-pdf-preview-modal
        :show="$showPreviewModal"
        :title="$previewJudul"
        :url="$previewUrl"
        onClose="tutupPreview"
    />
</div>
