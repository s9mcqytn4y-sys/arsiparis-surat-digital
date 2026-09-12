@props([
    'show' => false,
    'title' => '',
    'maxWidth' => '2xl',
    'onClose' => 'tutupModal',
])

@php
$maxWidthClass = match ($maxWidth) {
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    'full' => 'sm:max-w-full',
    default => 'sm:max-w-2xl',
};
@endphp

@if($show)
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop (Crisp Overlay without excessive screen blur) -->
        <div
            class="fixed inset-0 transition-opacity bg-slate-900/40"
            aria-hidden="true"
            wire:click="{{ $onClose }}"
        ></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Dialog Box -->
        <div class="relative z-10 inline-block align-bottom bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-2xl text-left overflow-hidden shadow-2xl transition-all sm:my-8 sm:align-middle {{ $maxWidthClass }} w-full border border-slate-200 dark:border-slate-700">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/80">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100" id="modal-title">
                        {{ $title }}
                    </h3>
                    @if(isset($subtitle))
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $subtitle }}</p>
                    @endif
                </div>
                <button
                    type="button"
                    wire:click="{{ $onClose }}"
                    class="p-1.5 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors cursor-pointer"
                    aria-label="Tutup Dialog"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content Slot -->
            <div class="px-6 py-5 max-h-[calc(100vh-220px)] overflow-y-auto">
                {{ $slot }}
            </div>

            <!-- Modal Footer Slot -->
            @if(isset($footer))
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-100 dark:border-slate-700 flex items-center justify-end gap-3">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
@endif
