<div class="relative" x-data="{ open: @entangle('isOpen') }">
    <button
        type="button"
        wire:click="toggleDropdown"
        aria-label="Notifikasi Persuratan, {{ $unreadCount }} belum dibaca"
        class="relative p-2 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 transition-colors min-h-[40px] min-w-[40px] flex items-center justify-center"
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
        class="absolute right-0 mt-2 w-80 sm:w-96 rounded-xl border border-slate-200 bg-white shadow-xl z-50 overflow-hidden"
        style="display: none;"
    >
        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 bg-slate-50">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Notifikasi Disposisi</h3>
                <p class="text-[11px] text-slate-500">{{ $unreadCount }} naskah butuh perhatian</p>
            </div>
            @if ($unreadCount > 0)
                <button
                    type="button"
                    wire:click="markAllAsRead"
                    class="text-[11px] font-semibold text-emerald-700 hover:text-emerald-800"
                >
                    Tandai Semua Dibaca
                </button>
            @endif
        </div>

        <ul class="divide-y divide-slate-100 max-h-80 overflow-y-auto" role="list">
            @forelse ($notifications as $notification)
                @php $data = $notification->data; @endphp
                <li class="p-3.5 hover:bg-slate-50 transition {{ $notification->read_at === null ? 'bg-emerald-50/40' : '' }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-900 truncate">
                                {{ $data['nomor_agenda'] ?? 'Naskah Dinas' }}
                            </p>
                            <p class="text-xs text-slate-700 font-medium line-clamp-1 mt-0.5">
                                {{ $data['perihal'] ?? '' }}
                            </p>
                            <p class="text-[11px] text-slate-500 mt-1 italic">
                                &ldquo;{{ $data['instruksi'] ?? 'Mohon ditindaklanjuti' }}&rdquo;
                            </p>
                            <div class="mt-2 flex items-center gap-3">
                                @if (!empty($data['stream_url']))
                                    <a
                                        href="{{ $data['stream_url'] }}"
                                        target="_blank"
                                        class="text-[11px] font-semibold text-emerald-700 hover:underline"
                                    >
                                        Buka Berkas PDF &rarr;
                                    </a>
                                @endif
                                @if ($notification->read_at === null)
                                    <button
                                        type="button"
                                        wire:click="markAsRead('{{ $notification->id }}')"
                                        class="text-[11px] text-slate-400 hover:text-slate-600"
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
                    Tidak ada notifikasi disposisi baru saat ini.
                </li>
            @endforelse
        </ul>
    </div>
</div>
