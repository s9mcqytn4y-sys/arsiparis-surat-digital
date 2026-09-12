@props([
    'show' => false,
    'url' => null,
    'title' => 'Pratinjau Dokumen Naskah Dinas',
    'onClose' => 'tutupPreview',
])

@if($show)
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="pdf-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop (Crisp Overlay without excessive screen blur) -->
        <div
            class="fixed inset-0 transition-opacity bg-slate-900/60"
            aria-hidden="true"
            wire:click="{{ $onClose }}"
        ></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Box -->
        <div class="relative z-10 inline-block align-bottom bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-2xl text-left overflow-hidden shadow-2xl transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full border border-slate-200 dark:border-slate-700">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 bg-slate-900 text-white">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-rose-500/20 text-rose-400 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white tracking-wide" id="pdf-modal-title">
                            {{ $title }}
                        </h3>
                        <p class="text-xs text-slate-400">Pratinjau Resmi Naskah Dinas Digital (Signed Route Terproteksi)</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($url)
                        <a
                            href="{{ $url }}"
                            target="_blank"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-white rounded-lg transition-colors border border-slate-700"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh
                        </a>
                    @endif
                    <button
                        type="button"
                        wire:click="{{ $onClose }}"
                        class="p-1.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-colors cursor-pointer"
                        aria-label="Tutup Pratinjau"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Iframe Container -->
            <div class="p-1 bg-slate-800">
                @if($url)
                    <iframe
                        src="{{ $url }}"
                        class="w-full h-[70vh] rounded-b-xl bg-white border-0"
                        title="{{ $title }}"
                    ></iframe>
                @else
                    <div class="flex flex-col items-center justify-center h-[50vh] text-slate-400">
                        <svg class="w-12 h-12 mb-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm font-medium">Dokumen tidak dapat dimuat atau belum diunggah.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
