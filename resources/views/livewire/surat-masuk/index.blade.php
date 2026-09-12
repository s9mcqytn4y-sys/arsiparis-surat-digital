<div>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('persuratan.surat_masuk.title') }}</h1>
            <p class="text-sm text-slate-500">Pencatatan buku agenda naskah dinas masuk di lingkungan universitas.</p>
        </div>
        <div class="flex items-center gap-3">
            <button
                type="button"
                wire:click="bukaExport"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-700 shadow-xs hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
            >
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Ekspor Agenda</span>
            </button>
            <a href="{{ route('surat-masuk.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700 focus-visible:ring-offset-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Catat Surat Masuk</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="mb-6 grid grid-cols-1 gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-3">
        <div>
            <label for="search-input" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Pencarian Naskah</label>
            <input
                id="search-input"
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Cari nomor, pengirim, perihal..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
            />
        </div>
        <div>
            <label for="status-filter" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Status Disposisi</label>
            <select
                id="status-filter"
                wire:model.live="status"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
            >
                <option value="">Semua Status</option>
                @foreach ($statuses as $st)
                    <option value="{{ $st->value }}">{{ $st->label() }}</option>
                @endforeach
            </select>
        </div>
        @if (auth()->user()->hasRole(['super_admin', 'rektor', 'wakil_rektor']))
        <div>
            <label for="unit-filter" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Unit Kerja Kampus</label>
            <select
                id="unit-filter"
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
                        <th scope="col" class="px-6 py-3.5">Agenda / Tanggal</th>
                        <th scope="col" class="px-6 py-3.5">Nomor & Pengirim</th>
                        <th scope="col" class="px-6 py-3.5">Perihal</th>
                        <th scope="col" class="px-6 py-3.5">Unit Tujuan</th>
                        <th scope="col" class="px-6 py-3.5">Disposisi</th>
                        <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($daftarSurat as $surat)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="font-mono text-xs font-bold text-slate-900">{{ $surat->nomor_agenda }}</span>
                                <div class="text-xs text-slate-500">Terima: {{ $surat->tanggal_terima?->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $surat->nomor_surat }}</div>
                                <div class="text-xs text-slate-600 font-medium">Dari: {{ $surat->pengirim }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <p class="line-clamp-2 text-slate-800">{{ $surat->perihal }}</p>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs font-medium text-slate-700">
                                {{ $surat->unitKerja?->kode_unit ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @php
                                    $badgeStyle = match ($surat->status_disposisi->value) {
                                        'menunggu' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'diteruskan' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        default => 'bg-slate-100 text-slate-800 border-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $badgeStyle }}">
                                    {{ $surat->status_disposisi->label() }}
                                </span>
                                @if ($surat->disposisiPegawai)
                                    <div class="mt-1 text-xs text-slate-500 font-medium truncate max-w-[140px]" title="{{ $surat->disposisiPegawai->nama_lengkap }}">
                                        -> {{ $surat->disposisiPegawai->nama_lengkap }}
                                    </div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-xs font-medium">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- View PDF Signed Stream -->
                                    <a
                                        href="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('documents.stream', now()->addMinutes(15), ['document' => $surat->id, 'type' => 'surat_masuk']) }}"
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

                                    <!-- Print Lembar Disposisi -->
                                    <a
                                        href="{{ route('cetak.disposisi', $surat->id) }}"
                                        target="_blank"
                                        title="Cetak Lembar Disposisi A4"
                                        class="rounded p-1.5 text-slate-600 hover:bg-slate-100 hover:text-emerald-700"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.076-.672-2.03-1.27-2.829H4.5A2.25 2.25 0 0 0 2.25 13.25v4.5A2.25 2.25 0 0 0 4.5 20h15a2.25 2.25 0 0 0 2.25-2.25v-4.5a2.25 2.25 0 0 0-2.25-2.25h-.95c-.598.799-1.03 1.753-1.27 2.829m-10.56 0A7.5 7.5 0 0 1 12 10.5c1.91 0 3.65.71 5 1.88" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6.75h-9a2.25 2.25 0 0 0-2.25 2.25v1.5h13.5v-1.5a2.25 2.25 0 0 0-2.25-2.25Z" />
                                        </svg>
                                    </a>

                                    <!-- Trigger Disposisi Modal -->
                                    @can('update', $surat)
                                    <button
                                        type="button"
                                        wire:click="bukaDisposisi('{{ $surat->id }}')"
                                        title="Kelola Disposisi"
                                        class="rounded p-1.5 text-slate-600 hover:bg-slate-100 hover:text-blue-700"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                        </svg>
                                    </button>
                                    @endcan

                                    <!-- Delete Letter -->
                                    @can('delete', $surat)
                                    <button
                                        type="button"
                                        wire:confirm="Yakin ingin menghapus naskah surat masuk ini dari sistem kearsipan?"
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <p class="mt-2 text-sm font-semibold text-slate-700">Belum ada naskah surat masuk</p>
                                <p class="text-xs text-slate-400">Naskah yang dicatat akan muncul di tabel agenda ini.</p>
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

    <!-- Disposisi Modal Container -->
    @if ($showDisposisiModal && $selectedSuratId)
        <livewire:surat-masuk.disposisi-modal :surat-id="$selectedSuratId" wire:key="disposisi-modal-{{ $selectedSuratId }}" />
    @endif

    <!-- Export Modal Container -->
    @if ($showExportModal)
        <livewire:surat-masuk.export-modal wire:key="export-modal-container" />
    @endif
</div>
