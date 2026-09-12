<div
    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 transition-opacity"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-export-title"
>
    <!-- Backdrop Overlay -->
    <div
        class="fixed inset-0 bg-slate-900/60 transition-opacity"
        aria-hidden="true"
        wire:click="$dispatch('tutup-export-modal')"
    ></div>

    <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-900/10">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <div>
                <h3 id="modal-export-title" class="text-lg font-bold text-slate-900">Ekspor Buku Agenda Naskah Masuk</h3>
                <p class="text-xs text-slate-500">Pilih rentang tanggal, unit kerja, serta format dokumen yang diinginkan.</p>
            </div>
            <button
                type="button"
                wire:click="$dispatch('tutup-export-modal')"
                class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600"
                aria-label="Tutup jendela ekspor"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Form -->
        <form wire:submit="export" class="mt-4 space-y-4">
            <!-- Rentang Tanggal -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="export-start-date" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Tanggal Mulai</label>
                    <input
                        id="export-start-date"
                        type="date"
                        wire:model="startDate"
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                        required
                    />
                    @error('startDate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="export-end-date" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Tanggal Selesai</label>
                    <input
                        id="export-end-date"
                        type="date"
                        wire:model="endDate"
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                        required
                    />
                    @error('endDate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Unit Kerja Filter -->
            @if ($isUnitLocked)
                <div class="rounded-lg bg-slate-50 p-3 border border-slate-200">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Cakupan Unit Kerja</span>
                    <p class="mt-0.5 text-sm font-medium text-slate-800">{{ auth()->user()->unitKerja?->nama_unit ?? 'Unit Kerja Terdaftar' }} (Terkunci Sesuai Wewenang)</p>
                </div>
            @else
                <div>
                    <label for="export-unit-id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Cakupan Unit Kerja</label>
                    <select
                        id="export-unit-id"
                        wire:model="unitId"
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                    >
                        <option value="">Seluruh Unit Kerja Terpadu</option>
                        @foreach ($daftarUnit as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->kode_unit }} — {{ $unit->nama_unit }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Format Dokumen -->
            <div>
                <span class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">Format Hasil Ekspor</span>
                <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-3">
                    <label class="flex cursor-pointer items-center gap-2.5 rounded-lg border p-3 transition-colors hover:bg-slate-50 {{ $format === 'pdf' ? 'border-emerald-600 bg-emerald-50/50' : 'border-slate-200' }}">
                        <input type="radio" wire:model.live="format" value="pdf" class="text-emerald-600 focus:ring-emerald-600" />
                        <div>
                            <span class="block text-xs font-bold text-slate-800">Berkas PDF</span>
                            <span class="block text-[11px] text-slate-500">A4 Landscape</span>
                        </div>
                    </label>

                    <label class="flex cursor-pointer items-center gap-2.5 rounded-lg border p-3 transition-colors hover:bg-slate-50 {{ $format === 'csv' ? 'border-emerald-600 bg-emerald-50/50' : 'border-slate-200' }}">
                        <input type="radio" wire:model.live="format" value="csv" class="text-emerald-600 focus:ring-emerald-600" />
                        <div>
                            <span class="block text-xs font-bold text-slate-800">CSV / Excel</span>
                            <span class="block text-[11px] text-slate-500">Spreadsheet</span>
                        </div>
                    </label>

                    <label class="flex cursor-pointer items-center gap-2.5 rounded-lg border p-3 transition-colors hover:bg-slate-50 {{ $format === 'print' ? 'border-emerald-600 bg-emerald-50/50' : 'border-slate-200' }}">
                        <input type="radio" wire:model.live="format" value="print" class="text-emerald-600 focus:ring-emerald-600" />
                        <div>
                            <span class="block text-xs font-bold text-slate-800">Pratinjau</span>
                            <span class="block text-[11px] text-slate-500">Cetak Browser</span>
                        </div>
                    </label>
                </div>
                @error('format') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-4">
                <button
                    type="button"
                    wire:click="$dispatch('tutup-export-modal')"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Mulai Unduh / Ekspor</span>
                </button>
            </div>
        </form>
    </div>
</div>
