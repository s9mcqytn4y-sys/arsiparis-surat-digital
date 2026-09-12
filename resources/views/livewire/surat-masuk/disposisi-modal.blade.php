<div
    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-disposisi-title"
>
    <!-- Backdrop Overlay -->
    <div
        class="fixed inset-0 bg-slate-900/60 transition-opacity"
        aria-hidden="true"
        wire:click="$parent.tutupDisposisi"
    ></div>

    <div class="relative z-10 w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl transition">
        <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h2 id="modal-disposisi-title" class="text-lg font-bold text-slate-900">Lembar Instruksi Disposisi</h2>
                <p class="text-xs text-slate-500">Penerusan naskah dinas ke pejabat unit struktural terkait.</p>
            </div>
            <button
                type="button"
                wire:click="$parent.tutupDisposisi"
                aria-label="Tutup jendela modal"
                class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-700"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        @if ($surat)
            <div class="mb-5 rounded-lg bg-slate-50 p-3.5 text-xs text-slate-700 space-y-1 border border-slate-200">
                <div><strong class="font-semibold text-slate-900">Nomor Agenda:</strong> {{ $surat->nomor_agenda }}</div>
                <div><strong class="font-semibold text-slate-900">Nomor Surat:</strong> {{ $surat->nomor_surat }}</div>
                <div><strong class="font-semibold text-slate-900">Pengirim:</strong> {{ $surat->pengirim }}</div>
                <div class="line-clamp-2"><strong class="font-semibold text-slate-900">Perihal:</strong> {{ $surat->perihal }}</div>
            </div>

            @if ($surat->riwayatDisposisi && $surat->riwayatDisposisi->isNotEmpty())
                <div class="mb-5 rounded-lg border border-slate-200 bg-white p-3.5">
                    <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-600">Linimasa Pendelegasian Berjenjang</h3>
                    <ol class="relative border-l border-emerald-500/30 ml-2 space-y-3">
                        @foreach ($surat->riwayatDisposisi as $riwayat)
                            <li class="ml-4">
                                <div class="absolute -left-1.5 mt-1 h-3 w-3 rounded-full border border-white bg-emerald-600"></div>
                                <time class="mb-0.5 text-[11px] font-normal leading-none text-slate-400">
                                    {{ $riwayat->created_at->translatedFormat('d M Y H:i') }}
                                </time>
                                <p class="text-xs font-semibold text-slate-800">
                                    Dari: {{ $riwayat->pemberiDisposisi?->nama ?? 'Pimpinan' }} &rarr; Ke: {{ $riwayat->penerimaDisposisi?->nama }}
                                </p>
                                <p class="text-xs text-slate-600 italic bg-slate-50 p-1.5 rounded mt-1 border border-slate-100">
                                    &ldquo;{{ $riwayat->instruksi }}&rdquo;
                                </p>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif
        @endif

        <form wire:submit="simpanDisposisi" class="space-y-4">
            <div>
                <label for="disposisi-pejabat" class="block text-sm font-semibold text-slate-700">
                    Disposisi Diteruskan Kepada <span class="text-red-500" aria-hidden="true">*</span>
                </label>
                <select
                    id="disposisi-pejabat"
                    wire:model="disposisiKepada"
                    required
                    aria-required="true"
                    @error('disposisiKepada') aria-invalid="true" aria-describedby="disposisi-pejabat-error" @enderror
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                >
                    <option value="">-- Pilih Pejabat Penerima --</option>
                    @foreach ($pejabatList as $pj)
                        <option value="{{ $pj->id }}">{{ $pj->nama }} ({{ $pj->jabatan }})</option>
                    @endforeach
                </select>
                @error('disposisiKepada')
                    <p id="disposisi-pejabat-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status-disposisi" class="block text-sm font-semibold text-slate-700">
                    Status Progres Naskah <span class="text-red-500" aria-hidden="true">*</span>
                </label>
                <select
                    id="status-disposisi"
                    wire:model="statusDisposisi"
                    required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                >
                    @foreach ($statuses as $st)
                        <option value="{{ $st->value }}">{{ $st->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="instruksi-disposisi" class="block text-sm font-semibold text-slate-700">
                    Instruksi / Catatan Pimpinan <span class="text-red-500" aria-hidden="true">*</span>
                </label>
                <textarea
                    id="instruksi-disposisi"
                    rows="3"
                    wire:model="instruksiDisposisi"
                    required
                    aria-required="true"
                    @error('instruksiDisposisi') aria-invalid="true" aria-describedby="instruksi-disposisi-error" @enderror
                    placeholder="Contoh: Mohon koordinasikan dengan Gugus Penjaminan Mutu dan siapkan draft balasan sebelum hari Jumat."
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                ></textarea>
                @error('instruksiDisposisi')
                    <p id="instruksi-disposisi-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                <button
                    type="button"
                    wire:click="$parent.tutupDisposisi"
                    class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100"
                >
                    Tutup
                </button>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="simpanDisposisi">Simpan Disposisi</span>
                    <span wire:loading wire:target="simpanDisposisi">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
