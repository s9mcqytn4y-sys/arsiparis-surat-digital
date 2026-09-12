<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <nav class="mb-1 text-xs text-slate-500 font-medium" aria-label="Breadcrumb">
                <ol class="flex items-center gap-1.5">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-emerald-700">Beranda</a></li>
                    <li><span class="text-slate-400">/</span></li>
                    <li><a href="{{ route('surat-keluar.index') }}" class="hover:text-emerald-700">Surat Keluar</a></li>
                    <li><span class="text-slate-400">/</span></li>
                    <li class="text-slate-800 font-semibold" aria-current="page">Terbitkan Nomor Baru</li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Penerbitan Nomor & Naskah Keluar</h1>
        </div>
        <a href="{{ route('surat-keluar.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-700">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form wire:submit="simpan" class="space-y-6">
            <!-- Pola Nomor & Unit Kerja -->
            <fieldset class="space-y-4">
                <legend class="text-base font-semibold text-slate-900 border-b border-slate-100 pb-2 w-full">
                    Pola Penomoran Resmi & Penandatangan
                </legend>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="master-nomor" class="block text-sm font-semibold text-slate-700">
                            Pola Klasifikasi Nomor Surat <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <select
                            id="master-nomor"
                            wire:model="masterNomorSuratId"
                            required
                            aria-required="true"
                            @error('masterNomorSuratId') aria-invalid="true" aria-describedby="master-nomor-error" @enderror
                            @class([
                                'mt-1 block w-full rounded-lg border px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600',
                                'border-red-500 ring-1 ring-red-500' => $errors->has('masterNomorSuratId'),
                                'border-slate-300' => ! $errors->has('masterNomorSuratId'),
                            ])
                        >
                            <option value="">-- Pilih Pola Klasifikasi --</option>
                            @foreach ($masterNomorList as $mn)
                                <option value="{{ $mn->id }}">{{ $mn->unitKerja?->kode_unit }} — Pola: {{ $mn->format_pola }} (Terakhir: #{{ $mn->nomor_terakhir }})</option>
                            @endforeach
                        </select>
                        @error('masterNomorSuratId')
                            <p id="master-nomor-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis-surat" class="block text-sm font-semibold text-slate-700">
                            Jenis Surat <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <select
                            id="jenis-surat"
                            wire:model.live="jenisSurat"
                            required
                            aria-required="true"
                            @error('jenisSurat') aria-invalid="true" aria-describedby="jenis-surat-error" @enderror
                            @class([
                                'mt-1 block w-full rounded-lg border px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600 bg-white',
                                'border-red-500 ring-1 ring-red-500' => $errors->has('jenisSurat'),
                                'border-slate-300' => ! $errors->has('jenisSurat'),
                            ])
                        >
                            <option value="">-- Pilih Jenis Surat Dinas --</option>
                            @foreach ($daftarJenisSurat as $jns)
                                <option value="{{ $jns }}">{{ $jns }}</option>
                            @endforeach
                            <option value="__custom__">+ Input Manual / Tambah Baru</option>
                        </select>

                        @if ($jenisSurat === '__custom__' || $isCustomJenisSurat)
                            <div class="mt-2">
                                <input
                                    type="text"
                                    wire:model="jenisSuratManual"
                                    placeholder="Masukkan jenis surat secara manual..."
                                    @class([
                                        'block w-full rounded-lg border px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600',
                                        'border-red-500 ring-1 ring-red-500 bg-red-50/20' => $errors->has('jenisSuratManual'),
                                        'border-slate-300' => ! $errors->has('jenisSuratManual'),
                                    ])
                                />
                                <p class="mt-1 text-xs text-slate-500">Masukkan jenis surat secara manual</p>
                                @error('jenisSuratManual')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        @error('jenisSurat')
                            <p id="jenis-surat-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </fieldset>

            <!-- Metadata Naskah Keluar -->
            <fieldset class="space-y-4">
                <legend class="text-base font-semibold text-slate-900 border-b border-slate-100 pb-2 w-full">
                    Detail Penerima & Isi Surat
                </legend>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="tujuan-surat" class="block text-sm font-semibold text-slate-700">
                            Tujuan Surat / Instansi Penerima <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <select
                            id="tujuan-surat"
                            wire:model.live="tujuanSurat"
                            required
                            aria-required="true"
                            @error('tujuanSurat') aria-invalid="true" aria-describedby="tujuan-surat-error" @enderror
                            @class([
                                'mt-1 block w-full rounded-lg border px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600 bg-white',
                                'border-red-500 ring-1 ring-red-500' => $errors->has('tujuanSurat'),
                                'border-slate-300' => ! $errors->has('tujuanSurat'),
                            ])
                        >
                            <option value="">-- Pilih Instansi / Pihak Tujuan Surat --</option>
                            @foreach ($daftarTujuanSurat as $tj)
                                <option value="{{ $tj }}">{{ $tj }}</option>
                            @endforeach
                            <option value="__custom__">+ Input Manual / Tambah Baru</option>
                        </select>

                        @if ($tujuanSurat === '__custom__' || $isCustomTujuanSurat)
                            <div class="mt-2">
                                <input
                                    type="text"
                                    wire:model="tujuanSuratManual"
                                    placeholder="Masukkan pihak tujuan secara manual..."
                                    @class([
                                        'block w-full rounded-lg border px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600',
                                        'border-red-500 ring-1 ring-red-500 bg-red-50/20' => $errors->has('tujuanSuratManual'),
                                        'border-slate-300' => ! $errors->has('tujuanSuratManual'),
                                    ])
                                />
                                <p class="mt-1 text-xs text-slate-500">Masukkan nama instansi / pihak tujuan secara manual</p>
                                @error('tujuanSuratManual')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        @error('tujuanSurat')
                            <p id="tujuan-surat-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal-surat-sk" class="block text-sm font-semibold text-slate-700">
                            Tanggal Surat <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="tanggal-surat-sk"
                            type="date"
                            wire:model="tanggalSurat"
                            required
                            aria-required="true"
                            @error('tanggalSurat') aria-invalid="true" aria-describedby="tanggal-surat-sk-error" @enderror
                            @class([
                                'mt-1 block w-full rounded-lg border px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600',
                                'border-red-500 ring-1 ring-red-500' => $errors->has('tanggalSurat'),
                                'border-slate-300' => ! $errors->has('tanggalSurat'),
                            ])
                        />
                        @error('tanggalSurat')
                            <p id="tanggal-surat-sk-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="perihal-sk" class="block text-sm font-semibold text-slate-700">
                        Perihal / Pokok Surat Keluar <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input
                        id="perihal-sk"
                        type="text"
                        wire:model="perihal"
                        required
                        aria-required="true"
                        @error('perihal') aria-invalid="true" aria-describedby="perihal-sk-error" @enderror
                        placeholder="Contoh: Permohonan Rekomendasi Pembukaan Program Studi Magister Ilmu Komputer"
                        @class([
                            'mt-1 block w-full rounded-lg border px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600',
                            'border-red-500 ring-1 ring-red-500' => $errors->has('perihal'),
                            'border-slate-300' => ! $errors->has('perihal'),
                        ])
                    />
                    @error('perihal')
                        <p id="perihal-sk-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="berkas-sk" class="block text-sm font-semibold text-slate-700">
                        Draf Berkas Digital PDF (Opsional)
                    </label>
                    <div class="mt-2 flex justify-center rounded-lg border border-dashed border-slate-300 px-6 py-6 text-center hover:bg-slate-50 transition">
                        <div class="space-y-2">
                            <svg class="mx-auto h-9 w-9 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <label for="berkas-sk" class="relative cursor-pointer rounded-md font-semibold text-emerald-700 hover:text-emerald-800 focus-within:outline-none">
                                    <span>Pilih draf PDF</span>
                                    <input
                                        id="berkas-sk"
                                        type="file"
                                        wire:model="berkas"
                                        accept="application/pdf"
                                        class="sr-only"
                                    />
                                </label>
                                <span class="pl-1">atau seret file ke sini</span>
                            </div>
                            <p class="text-xs text-slate-500">Maksimal 10 MB. Draf dapat diunggah kemudian bila surat belum ditandatangani.</p>
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
                        Memeriksa berkas draf surat keluar...
                    </div>
                </div>
            </fieldset>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('surat-keluar.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </a>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="simpan">Kunci Nomor & Terbitkan</span>
                    <span wire:loading wire:target="simpan">Mengunci Sequence...</span>
                </button>
            </div>
        </form>
    </div>
</div>
