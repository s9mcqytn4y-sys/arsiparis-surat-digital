<div class="space-y-6">
    <!-- Header Data Master & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-700 pb-4 transition-colors">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                Data Master
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
                {{ \App\Constants\AppConstants::SUBTITLE_DATA_MASTER }}
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- Backup Data Button (Download Icon Arrow Down) -->
            <button
                type="button"
                wire:click="backupData"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0b6361] hover:bg-[#084c4a] text-white text-xs font-semibold shadow-xs transition cursor-pointer active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M12 3v10.5m0 0l-4.5-4.5m4.5 4.5l4.5-4.5" />
                </svg>
                <span>Backup Data</span>
            </button>

            <!-- Import Data Button (Upload Icon Arrow Up) -->
            <button
                type="button"
                wire:click="openImportModal"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold shadow-xs transition cursor-pointer active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                <span>Import Data</span>
            </button>
        </div>
    </div>

    <!-- Tab Navigation Bar -->
    <div class="border-b border-slate-200 dark:border-slate-700">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs Master Data">
            <button
                type="button"
                wire:click="setTab('pegawai')"
                class="whitespace-nowrap py-3 px-1 border-b-2 font-bold text-xs transition cursor-pointer {{ $activeTab === 'pegawai' ? 'border-[#0d7a78] dark:border-teal-400 text-[#0d7a78] dark:text-teal-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}"
            >
                Data Pegawai
            </button>

            <button
                type="button"
                wire:click="setTab('nomor_surat')"
                class="whitespace-nowrap py-3 px-1 border-b-2 font-bold text-xs transition cursor-pointer {{ $activeTab === 'nomor_surat' ? 'border-[#0d7a78] dark:border-teal-400 text-[#0d7a78] dark:text-teal-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}"
            >
                Nomor Surat
            </button>

            <button
                type="button"
                wire:click="setTab('admin')"
                class="whitespace-nowrap py-3 px-1 border-b-2 font-bold text-xs transition cursor-pointer {{ $activeTab === 'admin' ? 'border-[#0d7a78] dark:border-teal-400 text-[#0d7a78] dark:text-teal-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}"
            >
                Data Admin
            </button>
        </nav>
    </div>

    <!-- TAB 1: DATA PEGAWAI -->
    @if ($activeTab === 'pegawai')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">Data Pegawai</h2>
                <button
                    type="button"
                    wire:click="openPegawaiModal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold shadow-xs transition cursor-pointer active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v16.5m8-8.5H4"/></svg>
                    <span>Tambah Pegawai</span>
                </button>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs overflow-hidden transition-colors">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-[#0d7a78] dark:bg-slate-700 text-white font-semibold text-xs tracking-wider">
                                <th scope="col" class="py-3.5 px-4 w-12 text-center">No</th>
                                <th scope="col" class="py-3.5 px-4 w-44">NIP</th>
                                <th scope="col" class="py-3.5 px-4">Nama</th>
                                <th scope="col" class="py-3.5 px-4 w-52">Jabatan</th>
                                <th scope="col" class="py-3.5 px-4 w-44">Pangkat/Golongan</th>
                                <th scope="col" class="py-3.5 px-4 w-28 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-200">
                            @forelse ($pegawaiList as $index => $pegawai)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                    <td class="py-3.5 px-4 text-center text-xs font-medium text-slate-500 dark:text-slate-400">
                                        {{ $pegawaiList->firstItem() + $index }}
                                    </td>
                                    <td class="py-3.5 px-4 text-xs font-bold text-slate-900 dark:text-slate-100 font-mono">
                                        {{ $pegawai->nip_nidn }}
                                    </td>
                                    <td class="py-3.5 px-4 text-xs font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $pegawai->nama }}
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-slate-700 dark:text-slate-300">
                                        {{ $pegawai->jabatan }}
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-slate-600 dark:text-slate-400">
                                        {{ $pegawai->golongan ?: '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button
                                                type="button"
                                                wire:click="openPegawaiModal('{{ $pegawai->id }}')"
                                                class="p-1.5 rounded-lg bg-sky-50 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 hover:bg-sky-100 transition cursor-pointer"
                                                title="Edit Data Pegawai"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                            </button>
                                            <button
                                                type="button"
                                                onclick="confirmDeletePegawai('{{ $pegawai->id }}', '{{ $pegawai->nama }}')"
                                                class="p-1.5 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 hover:bg-rose-100 transition cursor-pointer"
                                                title="Hapus Pegawai"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.08 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 text-xs italic">
                                        Belum ada data pegawai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($pegawaiList->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        {{ $pegawaiList->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- TAB 2: NOMOR SURAT -->
    @if ($activeTab === 'nomor_surat')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">Manajemen Nomor Surat</h2>
                <button
                    type="button"
                    wire:click="openNomorSuratModal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold shadow-xs transition cursor-pointer active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v16.5m8-8.5H4"/></svg>
                    <span>Tambah Nomor Surat</span>
                </button>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs overflow-hidden transition-colors">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-[#0d7a78] dark:bg-slate-700 text-white font-semibold text-xs tracking-wider">
                                <th scope="col" class="py-3.5 px-4 w-12 text-center">No</th>
                                <th scope="col" class="py-3.5 px-4 w-48">Nomor Surat</th>
                                <th scope="col" class="py-3.5 px-4 w-44">Jenis Surat</th>
                                <th scope="col" class="py-3.5 px-4 w-32">Tanggal Dibuat</th>
                                <th scope="col" class="py-3.5 px-4 w-28 text-center">Status</th>
                                <th scope="col" class="py-3.5 px-4">Keterangan</th>
                                <th scope="col" class="py-3.5 px-4 w-28 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-200">
                            @forelse ($nomorSuratList as $index => $ns)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                    <td class="py-3.5 px-4 text-center text-xs font-medium text-slate-500 dark:text-slate-400">
                                        {{ $nomorSuratList->firstItem() + $index }}
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-slate-100 text-xs font-mono">
                                        {{ $ns->format_pola }}
                                    </td>
                                    <td class="py-3.5 px-4 text-xs font-semibold text-slate-800 dark:text-slate-200">
                                        {{ $ns->nama_klasifikasi }}
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                        {{ $ns->tanggal_dibuat ? $ns->tanggal_dibuat->format('d/m/Y') : $ns->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $ns->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400' }}">
                                            {{ $ns->is_active ? 'Aktif' : 'Non-aktif' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-slate-600 dark:text-slate-300">
                                        {{ $ns->keterangan ?: '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button
                                                type="button"
                                                wire:click="openNomorSuratModal('{{ $ns->id }}')"
                                                class="p-1.5 rounded-lg bg-sky-50 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 hover:bg-sky-100 transition cursor-pointer"
                                                title="Edit Nomor Surat"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                            </button>
                                            <button
                                                type="button"
                                                onclick="confirmDeleteNomorSurat('{{ $ns->id }}', '{{ $ns->format_pola }}')"
                                                class="p-1.5 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 hover:bg-rose-100 transition cursor-pointer"
                                                title="Hapus Nomor Surat"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.08 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400 text-xs italic">
                                        Belum ada data nomor surat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($nomorSuratList->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        {{ $nomorSuratList->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- TAB 3: DATA ADMIN -->
    @if ($activeTab === 'admin')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">Data Administrator</h2>
                <button
                    type="button"
                    wire:click="openAdminModal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold shadow-xs transition cursor-pointer active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v16.5m8-8.5H4"/></svg>
                    <span>Tambah Admin</span>
                </button>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs overflow-hidden transition-colors">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-[#0d7a78] dark:bg-slate-700 text-white font-semibold text-xs tracking-wider">
                                <th scope="col" class="py-3.5 px-4 w-12 text-center">No</th>
                                <th scope="col" class="py-3.5 px-4 w-44">Username</th>
                                <th scope="col" class="py-3.5 px-4">Nama</th>
                                <th scope="col" class="py-3.5 px-4 w-48 text-center">Role</th>
                                <th scope="col" class="py-3.5 px-4 w-28 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-200">
                            @forelse ($adminList as $index => $admin)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                    <td class="py-3.5 px-4 text-center text-xs font-medium text-slate-500 dark:text-slate-400">
                                        {{ $adminList->firstItem() + $index }}
                                    </td>
                                    <td class="py-3.5 px-4 text-xs font-bold text-slate-900 dark:text-slate-100 font-mono">
                                        {{ explode('@', $admin->email)[0] ?: $admin->email }}
                                    </td>
                                    <td class="py-3.5 px-4 text-xs font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $admin->name }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-300 uppercase tracking-wide">
                                            {{ strtoupper(str_replace('_', ' ', $admin->roles->first()?->name ?: 'PETUGAS TU')) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button
                                                type="button"
                                                wire:click="openAdminModal('{{ $admin->id }}')"
                                                class="p-1.5 rounded-lg bg-sky-50 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 hover:bg-sky-100 transition cursor-pointer"
                                                title="Edit Admin"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                            </button>
                                            <button
                                                type="button"
                                                onclick="confirmDeleteAdmin('{{ $admin->id }}', '{{ $admin->name }}')"
                                                class="p-1.5 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 hover:bg-rose-100 transition cursor-pointer"
                                                title="Hapus Admin"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.08 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 text-xs italic">
                                        Belum ada data administrator.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($adminList->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        {{ $adminList->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- MODAL PEGAWAI -->
    @if ($modalPegawaiOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-pegawai-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-xs" wire:click="$set('modalPegawaiOpen', false)"></div>

                <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 shadow-2xl rounded-2xl sm:align-middle border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700 mb-4">
                        <h3 id="modal-pegawai-title" class="text-base font-bold text-slate-900 dark:text-slate-100">
                            {{ $pegawaiId ? 'Edit Data Pegawai' : 'Tambah Data Pegawai' }}
                        </h3>
                        <button type="button" wire:click="$set('modalPegawaiOpen', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="savePegawai" class="space-y-4">
                        <div>
                            <label for="pegawai_nip" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">NIP *</label>
                            <input
                                id="pegawai_nip"
                                type="text"
                                wire:model="pegawai_nip"
                                @class([
                                    'w-full px-3.5 py-2.5 text-sm border rounded-xl focus:outline-none transition dark:bg-slate-900 dark:text-slate-100',
                                    'border-rose-500 ring-1 ring-rose-500 bg-rose-50/20' => $errors->has('pegawai_nip'),
                                    'border-slate-300 dark:border-slate-700 focus:ring-[#0d7a78] focus:border-[#0d7a78]' => ! $errors->has('pegawai_nip'),
                                ])
                            >
                            @error('pegawai_nip')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="pegawai_nama" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama *</label>
                            <input
                                id="pegawai_nama"
                                type="text"
                                wire:model="pegawai_nama"
                                @class([
                                    'w-full px-3.5 py-2.5 text-sm border rounded-xl focus:outline-none transition dark:bg-slate-900 dark:text-slate-100',
                                    'border-rose-500 ring-1 ring-rose-500 bg-rose-50/20' => $errors->has('pegawai_nama'),
                                    'border-slate-300 dark:border-slate-700 focus:ring-[#0d7a78] focus:border-[#0d7a78]' => ! $errors->has('pegawai_nama'),
                                ])
                            >
                            @error('pegawai_nama')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="pegawai_jabatan" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Jabatan *</label>
                            <input
                                id="pegawai_jabatan"
                                type="text"
                                wire:model="pegawai_jabatan"
                                list="smartOpsiJabatan"
                                @class([
                                    'w-full px-3.5 py-2.5 text-sm border rounded-xl focus:outline-none transition dark:bg-slate-900 dark:text-slate-100',
                                    'border-rose-500 ring-1 ring-rose-500 bg-rose-50/20' => $errors->has('pegawai_jabatan'),
                                    'border-slate-300 dark:border-slate-700 focus:ring-[#0d7a78] focus:border-[#0d7a78]' => ! $errors->has('pegawai_jabatan'),
                                ])
                            >
                            <datalist id="smartOpsiJabatan">
                                @foreach ($opsiJabatan as $opsi)
                                    <option value="{{ $opsi }}"></option>
                                @endforeach
                            </datalist>
                            @error('pegawai_jabatan')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="pegawai_golongan" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pangkat/Golongan</label>
                            <input
                                id="pegawai_golongan"
                                type="text"
                                wire:model="pegawai_golongan"
                                placeholder="Contoh: Penata Muda / III/a"
                                list="smartOpsiGolongan"
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#0d7a78]"
                            >
                            <datalist id="smartOpsiGolongan">
                                @foreach ($opsiGolongan as $opsi)
                                    <option value="{{ $opsi }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <div>
                            <label for="pegawai_unit_id" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Unit Kerja *</label>
                            <select id="pegawai_unit_id" wire:model="pegawai_unit_id" class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl">
                                @foreach ($unitKerjaList as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama_unit }} ({{ $unit->kode_unit }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                            <button
                                type="button"
                                wire:click="$set('modalPegawaiOpen', false)"
                                class="px-5 py-2 rounded-xl bg-[#1e293b] hover:bg-slate-800 text-white text-xs font-semibold transition cursor-pointer"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="px-5 py-2 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold transition cursor-pointer"
                            >
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL NOMOR SURAT -->
    @if ($modalNomorSuratOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-ns-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-xs" wire:click="$set('modalNomorSuratOpen', false)"></div>

                <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 shadow-2xl rounded-2xl sm:align-middle border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700 mb-4">
                        <h3 id="modal-ns-title" class="text-base font-bold text-slate-900 dark:text-slate-100">
                            {{ $nomorSuratId ? 'Edit Nomor Surat' : 'Tambah Nomor Surat' }}
                        </h3>
                        <button type="button" wire:click="$set('modalNomorSuratOpen', false)" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveNomorSurat" class="space-y-4">
                        <div>
                            <label for="ns_nomor_surat" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Surat *</label>
                            <input
                                id="ns_nomor_surat"
                                type="text"
                                wire:model="ns_nomor_surat"
                                placeholder="Contoh: 001/TU/X/2026"
                                @class([
                                    'w-full px-3.5 py-2.5 text-sm border rounded-xl focus:outline-none transition dark:bg-slate-900 dark:text-slate-100',
                                    'border-rose-500 ring-1 ring-rose-500 bg-rose-50/20' => $errors->has('ns_nomor_surat'),
                                    'border-slate-300 dark:border-slate-700 focus:ring-[#0d7a78]' => ! $errors->has('ns_nomor_surat'),
                                ])
                            >
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Masukkan nomor surat secara manual</p>
                            @error('ns_nomor_surat')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ns_jenis_surat" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Jenis Surat *</label>
                            <select
                                id="ns_jenis_surat"
                                wire:model="ns_jenis_surat"
                                @class([
                                    'w-full px-3.5 py-2.5 text-sm border rounded-xl bg-white dark:bg-slate-900 dark:text-slate-100 focus:outline-none transition',
                                    'border-rose-500 ring-1 ring-rose-500' => $errors->has('ns_jenis_surat'),
                                    'border-slate-300 dark:border-slate-700 focus:ring-[#0d7a78]' => ! $errors->has('ns_jenis_surat'),
                                ])
                            >
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="Surat Undangan">Surat Undangan</option>
                                <option value="Surat Tugas">Surat Tugas</option>
                                <option value="Surat Edaran">Surat Edaran</option>
                                <option value="Surat Pengantar">Surat Pengantar</option>
                                <option value="Surat Keputusan (SK)">Surat Keputusan (SK)</option>
                                @foreach ($opsiJenisSurat as $opsi)
                                    <option value="{{ $opsi }}">{{ $opsi }}</option>
                                @endforeach
                            </select>
                            @error('ns_jenis_surat')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ns_tanggal_dibuat" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Dibuat *</label>
                            <input
                                id="ns_tanggal_dibuat"
                                type="date"
                                wire:model="ns_tanggal_dibuat"
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl"
                            >
                        </div>

                        <div>
                            <label for="ns_keterangan" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Keterangan</label>
                            <textarea
                                id="ns_keterangan"
                                rows="3"
                                wire:model="ns_keterangan"
                                placeholder="Keterangan tambahan (opsional)"
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl"
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                            <button
                                type="button"
                                wire:click="$set('modalNomorSuratOpen', false)"
                                class="px-5 py-2 rounded-xl bg-[#1e293b] hover:bg-slate-800 text-white text-xs font-semibold transition cursor-pointer"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="px-5 py-2 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold transition cursor-pointer"
                            >
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL ADMIN -->
    @if ($modalAdminOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-admin-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-xs" wire:click="$set('modalAdminOpen', false)"></div>

                <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 shadow-2xl rounded-2xl sm:align-middle border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700 mb-4">
                        <h3 id="modal-admin-title" class="text-base font-bold text-slate-900 dark:text-slate-100">
                            {{ $adminId ? 'Edit Data Admin' : 'Tambah Data Admin' }}
                        </h3>
                        <button type="button" wire:click="$set('modalAdminOpen', false)" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveAdmin" class="space-y-4">
                        <div>
                            <label for="admin_username" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Username *</label>
                            <input
                                id="admin_username"
                                type="text"
                                wire:model="admin_username"
                                @if($adminId) readonly @endif
                                @class([
                                    'w-full px-3.5 py-2.5 text-sm border rounded-xl focus:outline-none transition dark:bg-slate-900 dark:text-slate-100',
                                    'bg-slate-100 dark:bg-slate-900 text-slate-500 cursor-not-allowed border-slate-200 dark:border-slate-700' => (bool)$adminId,
                                    'border-rose-500 ring-1 ring-rose-500' => $errors->has('admin_username'),
                                    'border-slate-300 dark:border-slate-700 focus:ring-[#0d7a78]' => ! $errors->has('admin_username') && ! $adminId,
                                ])
                            >
                            @if($adminId)
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Username tidak dapat diubah</p>
                            @endif
                            @error('admin_username')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="admin_password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Password {{ $adminId ? '(Kosongkan jika tidak ingin mengubah)' : '*' }}
                            </label>
                            <input
                                id="admin_password"
                                type="password"
                                wire:model="admin_password"
                                @class([
                                    'w-full px-3.5 py-2.5 text-sm border rounded-xl focus:outline-none transition dark:bg-slate-900 dark:text-slate-100',
                                    'border-rose-500 ring-1 ring-rose-500' => $errors->has('admin_password'),
                                    'border-slate-300 dark:border-slate-700 focus:ring-[#0d7a78]' => ! $errors->has('admin_password'),
                                ])
                            >
                            @error('admin_password')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="admin_password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Password</label>
                            <input
                                id="admin_password_confirmation"
                                type="password"
                                wire:model="admin_password_confirmation"
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl"
                            >
                        </div>

                        <div>
                            <label for="admin_nama" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap *</label>
                            <input
                                id="admin_nama"
                                type="text"
                                wire:model="admin_nama"
                                @class([
                                    'w-full px-3.5 py-2.5 text-sm border rounded-xl focus:outline-none transition dark:bg-slate-900 dark:text-slate-100',
                                    'border-rose-500 ring-1 ring-rose-500' => $errors->has('admin_nama'),
                                    'border-slate-300 dark:border-slate-700 focus:ring-[#0d7a78]' => ! $errors->has('admin_nama'),
                                ])
                            >
                            @error('admin_nama')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="admin_role" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Role *</label>
                            <select id="admin_role" wire:model="admin_role" class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl">
                                @foreach ($rolesList as $role)
                                    <option value="{{ $role->name }}">{{ strtoupper(str_replace('_', ' ', $role->name)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="admin_unit_id" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Unit Kerja *</label>
                            <select id="admin_unit_id" wire:model="admin_unit_id" class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 rounded-xl">
                                @foreach ($unitKerjaList as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                            <button
                                type="button"
                                wire:click="$set('modalAdminOpen', false)"
                                class="px-5 py-2 rounded-xl bg-[#1e293b] hover:bg-slate-800 text-white text-xs font-semibold transition cursor-pointer"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="px-5 py-2 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold transition cursor-pointer"
                            >
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL IMPORT DATA MASTER -->
    @if ($modalImportOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-import-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-xs" wire:click="$set('modalImportOpen', false)"></div>

                <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 shadow-2xl rounded-2xl sm:align-middle border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700 mb-4">
                        <h3 id="modal-import-title" class="text-base font-bold text-slate-900 dark:text-slate-100">
                            Import Data Master
                        </h3>
                        <button type="button" wire:click="$set('modalImportOpen', false)" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="importData" class="space-y-4">
                        <div>
                            <label for="importFile" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pilih Berkas Cadangan (.json) *</label>
                            <input
                                type="file"
                                id="importFile"
                                wire:model="importFile"
                                accept=".json"
                                class="w-full text-xs text-slate-700 dark:text-slate-300 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-slate-700 file:text-[#0d7a78] dark:file:text-teal-300 hover:file:bg-teal-100 transition cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl p-1"
                            >
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Upload file cadangan berformat JSON untuk memulihkan record master data.</p>
                            @error('importFile')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                            <button
                                type="button"
                                wire:click="$set('modalImportOpen', false)"
                                class="px-5 py-2 rounded-xl bg-[#1e293b] hover:bg-slate-800 text-white text-xs font-semibold transition cursor-pointer"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="px-5 py-2 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold transition cursor-pointer flex items-center gap-1.5"
                            >
                                <span wire:loading.remove wire:target="importData">Proses Import</span>
                                <span wire:loading wire:target="importData">Mengimpor...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- SweetAlert2 Delete Confirmations -->
    <script>
        function confirmDeletePegawai(id, nama) {
            Swal.fire({
                title: 'Konfirmasi Hapus Pegawai',
                text: 'Apakah Anda yakin ingin menghapus data pegawai "' + nama + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 shadow-2xl text-xs font-sans'
                }
            }).then((res) => {
                if (res.isConfirmed) {
                    @this.call('deletePegawai', id);
                }
            });
        }

        function confirmDeleteNomorSurat(id, nomor) {
            Swal.fire({
                title: 'Konfirmasi Hapus Nomor Surat',
                text: 'Apakah Anda yakin ingin menghapus nomor surat "' + nomor + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 shadow-2xl text-xs font-sans'
                }
            }).then((res) => {
                if (res.isConfirmed) {
                    @this.call('deleteNomorSurat', id);
                }
            });
        }

        function confirmDeleteAdmin(id, nama) {
            Swal.fire({
                title: 'Konfirmasi Hapus Administrator',
                text: 'Apakah Anda yakin ingin menghapus akun administrator "' + nama + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 shadow-2xl text-xs font-sans'
                }
            }).then((res) => {
                if (res.isConfirmed) {
                    @this.call('deleteAdmin', id);
                }
            });
        }
    </script>
</div>
