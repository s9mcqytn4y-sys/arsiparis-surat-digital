<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <nav class="mb-1 text-xs text-slate-500 font-medium" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1.5">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-emerald-700">Beranda</a></li>
                    <li><span class="text-slate-400">/</span></li>
                    <li><a href="{{ route('surat-masuk.index') }}" class="hover:text-emerald-700">Surat Masuk</a></li>
                    <li><span class="text-slate-400">/</span></li>
                    <li class="text-slate-800 font-semibold" aria-current="page">Catat Surat Baru</li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Registrasi Naskah Masuk</h1>
        </div>
        <a href="{{ route('surat-masuk.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-700">
            &larr; Kembali ke Agenda
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form wire:submit="simpan" class="space-y-6">
            <!-- Data Naskah Asal -->
            <fieldset class="space-y-4">
                <legend class="text-base font-semibold text-slate-900 border-b border-slate-100 pb-2 w-full">
                    Identitas Naskah Surat
                </legend>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="nomor-surat" class="block text-sm font-semibold text-slate-700">
                            Nomor Surat Asal <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="nomor-surat"
                            type="text"
                            wire:model="nomorSurat"
                            required
                            aria-required="true"
                            @error('nomorSurat') aria-invalid="true" aria-describedby="nomor-surat-error" @enderror
                            placeholder="Contoh: 120/B/DIKTI/VIII/2026"
                            class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600 @error('nomorSurat') border-red-500 @enderror"
                        />
                        @error('nomorSurat')
                            <p id="nomor-surat-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pengirim" class="block text-sm font-semibold text-slate-700">
                            Instansi / Pejabat Pengirim <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="pengirim"
                            type="text"
                            wire:model="pengirim"
                            required
                            aria-required="true"
                            @error('pengirim') aria-invalid="true" aria-describedby="pengirim-error" @enderror
                            placeholder="Contoh: Ditjen Pendidikan Tinggi Kemendikbudristek"
                            class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600 @error('pengirim') border-red-500 @enderror"
                        />
                        @error('pengirim')
                            <p id="pengirim-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="tanggal-surat" class="block text-sm font-semibold text-slate-700">
                            Tanggal Tertulis di Surat <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="tanggal-surat"
                            type="date"
                            wire:model="tanggalSurat"
                            required
                            aria-required="true"
                            @error('tanggalSurat') aria-invalid="true" aria-describedby="tanggal-surat-error" @enderror
                            class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600 @error('tanggalSurat') border-red-500 @enderror"
                        />
                        @error('tanggalSurat')
                            <p id="tanggal-surat-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal-terima" class="block text-sm font-semibold text-slate-700">
                            Tanggal Diterima Tata Usaha <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="tanggal-terima"
                            type="date"
                            wire:model="tanggalTerima"
                            required
                            aria-required="true"
                            @error('tanggalTerima') aria-invalid="true" aria-describedby="tanggal-terima-error" @enderror
                            class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600 @error('tanggalTerima') border-red-500 @enderror"
                        />
                        @error('tanggalTerima')
                            <p id="tanggal-terima-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="perihal" class="block text-sm font-semibold text-slate-700">
                        Perihal / Pokok Isi Surat <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input
                        id="perihal"
                        type="text"
                        wire:model="perihal"
                        required
                        aria-required="true"
                        @error('perihal') aria-invalid="true" aria-describedby="perihal-error" @enderror
                        placeholder="Contoh: Undangan Koordinasi Akreditasi Program Studi Internasional"
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600 @error('perihal') border-red-500 @enderror"
                    />
                    @error('perihal')
                        <p id="perihal-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ringkasan" class="block text-sm font-semibold text-slate-700">
                        Ringkasan / Catatan Tambahan (Opsional)
                    </label>
                    <textarea
                        id="ringkasan"
                        rows="3"
                        wire:model="ringkasan"
                        placeholder="Catatan ringkas instruksi atau rincian lampiran..."
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                    ></textarea>
                </div>
            </fieldset>

            <!-- Unit Tujuan & Berkas Scan -->
            <fieldset class="space-y-4">
                <legend class="text-base font-semibold text-slate-900 border-b border-slate-100 pb-2 w-full">
                    Unit Penerima & Berkas Digital
                </legend>

                @if (auth()->user()->hasRole(['super_admin', 'rektor', 'wakil_rektor']))
                <div>
                    <label for="unit-kerja" class="block text-sm font-semibold text-slate-700">
                        Unit Kerja Penerima <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <select
                        id="unit-kerja"
                        wire:model="unitKerjaId"
                        required
                        aria-required="true"
                        @error('unitKerjaId') aria-invalid="true" aria-describedby="unit-kerja-error" @enderror
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                    >
                        <option value="">-- Pilih Unit Kerja Kampus --</option>
                        @foreach ($daftarUnit as $u)
                            <option value="{{ $u->id }}">{{ $u->kode_unit }} — {{ $u->nama_unit }}</option>
                        @endforeach
                    </select>
                    @error('unitKerjaId')
                        <p id="unit-kerja-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <div>
                    <label for="berkas" class="block text-sm font-semibold text-slate-700">
                        Pindaian Berkas Digital PDF <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <div class="mt-2 flex justify-center rounded-lg border border-dashed border-slate-300 px-6 py-8 text-center hover:bg-slate-50 transition">
                        <div class="space-y-2">
                            <svg class="mx-auto h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <label for="berkas" class="relative cursor-pointer rounded-md font-semibold text-emerald-700 hover:text-emerald-800 focus-within:outline-none">
                                    <span>Pilih berkas PDF</span>
                                    <input
                                        id="berkas"
                                        type="file"
                                        wire:model="berkas"
                                        accept="application/pdf"
                                        required
                                        class="sr-only"
                                    />
                                </label>
                                <span class="pl-1">atau seret ke area ini</span>
                            </div>
                            <p class="text-xs text-slate-500">Maksimal ukuran berkas 10 MB. Validasi byte header (%PDF-) ditegakkan secara ketat.</p>
                            @if ($berkas)
                                <div class="mt-2 text-xs font-semibold text-emerald-700">
                                    Berkas siap diunggah: {{ $berkas->getClientOriginalName() }}
                                </div>
                            @endif
                        </div>
                    </div>
                    @error('berkas')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="berkas" class="mt-2 text-xs text-blue-600 font-medium">
                        Memeriksa dan mengunggah berkas sementara...
                    </div>
                </div>
            </fieldset>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('surat-masuk.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </a>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="simpan">Simpan ke Agenda</span>
                    <span wire:loading wire:target="simpan">Menyimpan Naskah...</span>
                </button>
            </div>
        </form>
    </div>
</div>
