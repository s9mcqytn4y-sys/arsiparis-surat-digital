<div>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('persuratan.surat_keluar.title') }}</h1>
            <p class="text-sm text-slate-500">Penomoran otomatis anti-duplikasi dan repositori naskah dinas keluar perguruan tinggi.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('surat-keluar.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Terbitkan Surat Keluar</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="mb-6 grid grid-cols-1 gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-3">
        <div class="sm:col-span-2">
            <label for="search-input-sk" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Pencarian Naskah Keluar</label>
            <input
                id="search-input-sk"
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Cari nomor surat, register, tujuan, atau perihal..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
            />
        </div>
        @if (auth()->user()->hasRole(['super_admin', 'rektor', 'wakil_rektor']))
        <div>
            <label for="unit-filter-sk" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Unit Kerja Kampus</label>
            <select
                id="unit-filter-sk"
                wire:model.live="unitId"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
            >
                <option value="">Semua Unit Kerja</option>
                @foreach ($daftarUnit as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->kode_unit }} — {{ $unit->nama_unit }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    <!-- Table Section -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-700">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">Register & Tanggal</th>
                        <th scope="col" class="px-6 py-3.5">Nomor Surat Resmi</th>
                        <th scope="col" class="px-6 py-3.5">Tujuan & Perihal</th>
                        <th scope="col" class="px-6 py-3.5">Penandatangan</th>
                        <th scope="col" class="px-6 py-3.5">Unit Kerja</th>
                        <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($daftarSurat as $surat)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="font-mono text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded">
                                    {{ $surat->nomor_register }}
                                </span>
                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $surat->tanggal_surat?->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-mono text-sm font-semibold text-slate-900">{{ $surat->nomor_surat }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <div class="font-semibold text-slate-900 text-xs">Kepada: {{ $surat->tujuan_surat }}</div>
                                <p class="line-clamp-2 text-slate-700 mt-0.5">{{ $surat->perihal }}</p>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs">
                                <div class="font-semibold text-slate-900">{{ $surat->penandatangan?->nama_lengkap ?? '-' }}</div>
                                <div class="text-slate-500">{{ $surat->penandatangan?->jabatan ?? '' }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs font-medium text-slate-700">
                                {{ $surat->unitKerja?->kode_unit ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-xs font-medium">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- View PDF Signed Stream if available -->
                                    @if ($surat->file_path)
                                    <a
                                        href="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('documents.stream', now()->addMinutes(15), ['document' => $surat->id, 'type' => 'surat_keluar']) }}"
                                        target="_blank"
                                        rel="noopener"
                                        title="Pratinjau Berkas PDF"
                                        class="rounded p-1.5 text-slate-600 hover:bg-slate-100 hover:text-emerald-700"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- Delete Letter -->
                                    @can('delete', $surat)
                                    <button
                                        type="button"
                                        wire:confirm="Yakin ingin menghapus naskah surat keluar ini?"
                                        wire:click="hapus('{{ $surat->id }}')"
                                        title="Hapus Naskah"
                                        class="rounded p-1.5 text-slate-600 hover:bg-red-50 hover:text-red-700"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                                <p class="mt-2 text-sm font-semibold text-slate-700">Belum ada naskah surat keluar</p>
                                <p class="text-xs text-slate-400">Klik "Terbitkan Surat Keluar" untuk meng-generate nomor surat otomatis.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($daftarSurat->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $daftarSurat->links() }}
            </div>
        @endif
    </div>
</div>
