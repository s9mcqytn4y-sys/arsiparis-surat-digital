<div>
    <!-- Header Halaman Arsip Digital -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Arsip Digital</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
                Penyimpanan dokumen institusi, sertifikat akreditasi, dan SK resmi perguruan tinggi.
            </p>
        </div>

        <button
            type="button"
            wire:click="bukaTambahModal"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold shadow-xs transition cursor-pointer active:scale-95"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Upload Dokumen</span>
        </button>
    </div>

    <!-- Card Filter & Pencarian -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs p-4 mb-6 transition-colors">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <!-- Pencarian Kata Kunci -->
            <div class="md:col-span-6">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama dokumen..."
                        class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#0d7a78] focus:border-[#0d7a78] transition"
                    >
                </div>
            </div>

            <!-- Filter Kategori -->
            <div class="md:col-span-4">
                <select
                    wire:model.live="filterKategori"
                    class="w-full px-3 py-2 text-sm border border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#0d7a78] focus:border-[#0d7a78] transition bg-white"
                >
                    <option value="">Semua Kategori</option>
                    @foreach ($daftarKategori as $kat)
                        <option value="{{ $kat }}">{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Reset Button -->
            <div class="md:col-span-2">
                <button
                    type="button"
                    wire:click="resetFilter"
                    class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl bg-[#1e293b] hover:bg-slate-800 text-white text-xs font-semibold transition cursor-pointer active:scale-95"
                >
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Grid Kartu Dokumen Arsip -->
    @if ($daftarArsip->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 p-12 text-center">
            <div class="mx-auto w-12 h-12 rounded-xl bg-teal-50 dark:bg-slate-700 flex items-center justify-center text-[#0d7a78] dark:text-teal-300 mb-3">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Tidak ada dokumen arsip ditemukan</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto mt-1">
                @if ($search !== '' || $filterKategori !== '')
                    Tidak menemukan dokumen yang sesuai dengan kata kunci atau filter pilihan Anda.
                @else
                    Belum ada dokumen arsip digital yang diunggah ke repositori unit kerja ini.
                @endif
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            @foreach ($daftarArsip as $arsip)
                @php
                    $isWord = str_contains(strtolower($arsip->file_mime ?? ''), 'word') || str_ends_with(strtolower($arsip->file_path ?? ''), '.doc') || str_ends_with(strtolower($arsip->file_path ?? ''), '.docx');
                    $katLabel = is_object($arsip->kategori) ? $arsip->kategori->label() : (string) $arsip->kategori;
                @endphp
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs p-5 flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-600 transition duration-150">
                    <div>
                        <!-- Atas: Ikon Tipe Dokumen & Judul -->
                        <div class="flex items-start gap-3.5 mb-3">
                            <div class="shrink-0">
                                @if ($isWord)
                                    <!-- Word Icon -->
                                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-xs">
                                        W
                                    </div>
                                @else
                                    <!-- Crisp Vector PDF Icon -->
                                    <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                        PDF
                                    </div>
                                @endif
                            </div>

                            <div class="grow min-w-0">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate" title="{{ $arsip->judul }}">
                                    {{ $arsip->judul }}
                                </h3>

                                <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                    </svg>
                                    <span class="truncate">{{ $katLabel }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Info: Tanggal & Berkas Attachment Link -->
                        <div class="space-y-1.5 text-xs text-slate-500 dark:text-slate-400 border-t border-slate-100 dark:border-slate-700 pt-3 mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <span>{{ $arsip->tanggal_dokumen ? $arsip->tanggal_dokumen->format('d/m/Y') : '-' }}</span>
                            </div>

                            @if ($arsip->file_path)
                                <div class="flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94a3 3 0 114.243 4.242L8.586 18.312a1.5 1.5 0 11-2.122-2.122l8.705-8.706" />
                                    </svg>
                                    <span class="truncate text-slate-600 dark:text-slate-300 font-medium" title="{{ basename($arsip->file_path) }}">
                                        {{ basename($arsip->file_path) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Aksi Kunci -->
                    <div class="flex items-center gap-1.5 pt-3 border-t border-slate-100 dark:border-slate-700">
                        @if ($arsip->file_path)
                            <!-- Pratinjau -->
                            <button
                                type="button"
                                wire:click="bukaPreview('{{ $arsip->id }}')"
                                title="Pratinjau Dokumen"
                                class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 rounded-lg bg-teal-50 dark:bg-slate-700 text-[#0d7a78] dark:text-teal-300 text-xs font-semibold transition cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12c1.074-4.65 5.238-8 10.244-8 5.006 0 9.17 3.35 10.244 8-1.074 4.65-5.238 8-10.244 8-5.006 0-9.17-3.35-10.244-8z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Pratinjau</span>
                            </button>

                            <!-- Unduh -->
                            <a
                                href="{{ URL::temporarySignedRoute('documents.download', now()->addMinutes(15), ['document' => $arsip->id]) }}"
                                title="Unduh Dokumen"
                                class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold transition cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                <span>Download</span>
                            </a>
                        @endif

                        <!-- Edit -->
                        <button
                            type="button"
                            wire:click="bukaEditModal('{{ $arsip->id }}')"
                            title="Edit Dokumen"
                            class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            <span>Edit</span>
                        </button>

                        <!-- Hapus dengan SweetAlert2 Modal -->
                        <button
                            type="button"
                            onclick="confirmDeleteArsip('{{ $arsip->id }}', '{{ addslashes($arsip->judul) }}')"
                            title="Hapus Dokumen"
                            class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold transition cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                            <span>Hapus</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination Navigasi -->
        <div class="mt-4">
            {{ $daftarArsip->links() }}
        </div>
    @endif

    <!-- Form Modal Component -->
    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition" role="dialog" aria-modal="true">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                        {{ $isEditing ? 'Edit Arsip Digital' : 'Upload Arsip Digital' }}
                    </h3>
                    <button
                        type="button"
                        wire:click="tutupModal"
                        class="text-slate-400 hover:text-slate-600 focus:outline-none p-1 rounded-lg hover:bg-slate-100 transition cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body Modal Form -->
                <form wire:submit.prevent="simpan" class="p-6 space-y-4.5">
                    <!-- Nama Dokumen -->
                    <div>
                        <label for="form-arsip-judul" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Nama Dokumen <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="form-arsip-judul"
                            type="text"
                            wire:model="judul"
                            placeholder="Contoh: SK Dekan tentang Pengangkatan Pembimbing"
                            class="w-full px-3.5 py-2.5 text-sm border rounded-xl transition-colors focus:outline-none focus:ring-1 dark:bg-slate-900 dark:text-slate-100 border-slate-300 dark:border-slate-700 focus:border-[#0d7a78]"
                        >
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="form-arsip-kategori" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Kategori <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="form-arsip-kategori"
                            wire:model.live="kategori"
                            class="w-full px-3.5 py-2.5 text-sm border rounded-xl transition-colors focus:outline-none focus:ring-1 bg-white dark:bg-slate-900 dark:text-slate-100 border-slate-300 dark:border-slate-700 focus:border-[#0d7a78]"
                        >
                            <option value="">Contoh: Surat, Laporan, SK, Notulen, dll</option>
                            @foreach ($daftarKategori as $katItem)
                                <option value="{{ $katItem }}">{{ $katItem }}</option>
                            @endforeach
                            <option value="__custom__">+ Input Manual / Tambah Baru</option>
                        </select>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="form-arsip-tanggal" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Tanggal <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="form-arsip-tanggal"
                            type="date"
                            wire:model="tanggal_dokumen"
                            class="w-full px-3.5 py-2.5 text-sm border rounded-xl transition-colors focus:outline-none focus:ring-1 dark:bg-slate-900 dark:text-slate-100 border-slate-300 dark:border-slate-700 focus:border-[#0d7a78]"
                        >
                    </div>

                    <!-- File Dokumen -->
                    <div>
                        <label for="form-arsip-file" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            File Dokumen (PDF, DOC/DOCX)
                        </label>
                        <input
                            id="form-arsip-file"
                            type="file"
                            wire:model="file_dokumen"
                            accept=".pdf,.doc,.docx"
                            class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-slate-700 file:text-[#0d7a78] dark:file:text-teal-300 hover:file:bg-teal-100 cursor-pointer"
                        >
                    </div>

                    <!-- Action Buttons Modal Footer -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button
                            type="button"
                            wire:click="tutupModal"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold shadow-xs transition cursor-pointer active:scale-95"
                        >
                            <span>{{ $isEditing ? 'Simpan Perubahan' : 'Terbitkan & Simpan' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Pratinjau Dokumen PDF Stream -->
    @if ($showPreviewModal && $previewUrl)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition" role="dialog" aria-modal="true">
            <div class="relative w-full max-w-4xl bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-2xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 flex flex-col h-[85vh]">
                <div class="flex items-center justify-between px-6 py-3.5 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                    <div class="flex items-center gap-2.5 truncate">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate">
                            Pratinjau: {{ $previewJudul }}
                        </h3>
                    </div>

                    <button
                        type="button"
                        wire:click="tutupPreview"
                        class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-200 transition cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="grow bg-slate-100 dark:bg-slate-900 relative">
                    <iframe
                        src="{{ $previewUrl }}"
                        class="w-full h-full border-0"
                        title="Viewer Dokumen"
                    ></iframe>
                </div>

                <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex items-center justify-between text-xs text-slate-500">
                    <span>Tautan terenkripsi berlaku 15 menit.</span>
                    <button
                        type="button"
                        wire:click="tutupPreview"
                        class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 dark:text-slate-200 hover:bg-slate-200 text-slate-800 font-semibold transition cursor-pointer"
                    >
                        Tutup Pratinjau
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- SweetAlert2 Konfirmasi Hapus Dokumen Arsip -->
    <script>
        function confirmDeleteArsip(id, judul) {
            const dark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: 'Konfirmasi Hapus Dokumen Arsip',
                text: 'Apakah Anda yakin ingin menghapus dokumen "' + judul + '" dari repositori digital?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                background: dark ? '#1e293b' : '#ffffff',
                color: dark ? '#f8fafc' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl border shadow-2xl text-xs font-sans ' + (dark ? 'border-slate-700' : 'border-slate-200'),
                    confirmButton: 'px-4 py-2 text-xs font-bold rounded-xl text-white shadow-xs',
                    cancelButton: 'px-4 py-2 text-xs font-bold rounded-xl text-white shadow-xs'
                }
            }).then((res) => {
                if (res.isConfirmed) {
                    @this.call('hapus', id);
                }
            });
        }
    </script>
</div>
