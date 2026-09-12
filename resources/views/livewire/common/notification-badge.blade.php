<div class="relative" x-data="{ open: @entangle('isOpen') }">
    <button
        type="button"
        wire:click="toggleDropdown"
        aria-label="Notifikasi Sistem, {{ $unreadCount }} belum dibaca"
        class="relative p-2 rounded-lg bg-[#0a5c5a] dark:bg-slate-700 hover:bg-[#0e706e] dark:hover:bg-slate-600 border border-teal-600/50 dark:border-slate-600 text-teal-100 hover:text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white min-h-9 min-w-9 flex items-center justify-center cursor-pointer active:scale-95"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        @if ($unreadCount > 0)
            <span
                class="absolute top-1.5 right-1.5 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-rose-600 rounded-full"
                role="status"
                aria-live="polite"
            >
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Panel -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 shadow-xl z-50 overflow-hidden"
        style="display: none;"
    >
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 px-4 py-3 bg-slate-50 dark:bg-slate-800">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-100">Notifikasi System & Persuratan</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">{{ $unreadCount }} pemberitahuan belum dibaca</p>
            </div>
            @if ($unreadCount > 0)
                <button
                    type="button"
                    wire:click="markAllAsRead"
                    class="text-[11px] font-semibold text-[#0d7a78] dark:text-teal-400 hover:underline cursor-pointer"
                >
                    Tandai Semua Dibaca
                </button>
            @endif
        </div>

        <ul class="divide-y divide-slate-100 dark:divide-slate-700 max-h-80 overflow-y-auto" role="list">
            @forelse ($notifications as $notification)
                @php $data = $notification->data; @endphp
                <li class="p-3.5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition {{ $notification->read_at === null ? 'bg-teal-50/40 dark:bg-slate-700/30' : '' }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate">
                                    {{ $data['judul'] ?? $data['nomor_agenda'] ?? 'Pemberitahuan Sistem' }}
                                </p>
                                <span class="text-[10px] text-slate-400">
                                    {{ $notification->created_at ? $notification->created_at->diffForHumans() : '' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-700 dark:text-slate-300 font-medium line-clamp-2 mt-0.5">
                                {{ $data['pesan'] ?? $data['perihal'] ?? '' }}
                            </p>
                            @if (!empty($data['instruksi']))
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 italic">
                                    &ldquo;{{ $data['instruksi'] }}&rdquo;
                                </p>
                            @endif
                            <div class="mt-2 flex items-center gap-3">
                                @if (!empty($data['stream_url']))
                                    <a
                                        href="{{ $data['stream_url'] }}"
                                        target="_blank"
                                        class="text-[11px] font-semibold text-[#0d7a78] dark:text-teal-400 hover:underline"
                                    >
                                        Buka Berkas PDF &rarr;
                                    </a>
                                @endif
                                @if ($notification->read_at === null)
                                    <button
                                        type="button"
                                        wire:click="markAsRead('{{ $notification->id }}')"
                                        class="text-[11px] text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer"
                                    >
                                        Tandai dibaca
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-6 text-center text-xs text-slate-400">
                    Tidak ada notifikasi sistem baru saat ini.
                </li>
            @endforelse
        </ul>
    </div>
</div>
