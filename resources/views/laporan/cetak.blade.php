<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Kearsipan - {{ $pengaturan->nama_institusi }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body {
                background: white !important;
                color: black !important;
                font-size: 10pt !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            @page {
                size: A4 portrait;
                margin: 15mm 15mm 15mm 15mm;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                max-width: 100% !important;
                margin: 0 !important;
            }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 font-serif text-xs p-6" onload="window.print()">

    <!-- Bar Aksi Atas (Tersembunyi saat dicetak) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-2xl shadow-xs border border-slate-200 font-sans">
        <div>
            <h2 class="font-bold text-slate-900 text-sm">Pratinjau Cetak Laporan Kearsipan</h2>
            <p class="text-slate-500 text-xs mt-0.5">Dokumen resmi dalam format standar cetak A4 kedinasan.</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-[#0d7a78] hover:bg-[#0a5c5a] text-white font-semibold rounded-xl text-xs cursor-pointer shadow-xs transition">
                Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs cursor-pointer transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- Lembar Dokumen Cetak Formal A4 -->
    <div class="print-container max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-slate-200 space-y-5">

        <!-- Kop Surat Resmi (Border Dobel Tebal Sesuai Standar Institusi) -->
        <div class="border-b-4 border-double border-slate-900 pb-3 flex items-center gap-4">
            @if ($pengaturan->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($pengaturan->logo_path))
                <img src="{{ \Illuminate\Support\Facades\Storage::url($pengaturan->logo_path) }}" alt="Logo" class="w-16 h-16 object-contain shrink-0">
            @else
                <div class="w-16 h-16 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xl shrink-0 font-sans">
                    U
                </div>
            @endif

            <div class="grow font-sans">
                <h1 class="text-base font-extrabold text-slate-900 uppercase tracking-tight">
                    {{ $pengaturan->nama_institusi }}
                </h1>
                @if ($pengaturan->nama_fakultas)
                    <p class="text-xs font-bold text-slate-800 uppercase tracking-wide">
                        {{ $pengaturan->nama_fakultas }}
                    </p>
                @endif
                <p class="text-[10px] text-slate-600 mt-1 leading-tight font-normal">
                    {{ $pengaturan->alamat_lengkap }}
                    @if ($pengaturan->telepon) | Telp: {{ $pengaturan->telepon }} @endif
                    @if ($pengaturan->email) | Email: {{ $pengaturan->email }} @endif
                    @if ($pengaturan->website) | Web: {{ $pengaturan->website }} @endif
                </p>
            </div>
        </div>

        <!-- Judul Laporan Formal -->
        <div class="text-center space-y-1 pt-1 font-sans">
            <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wide">
                LAPORAN REKAPITULASI SURAT DINAS &amp; DOKUMEN KEARSIPAN
            </h2>
            <p class="text-xs font-semibold text-slate-700">
                Periode: {{ date('d/m/Y', strtotime($dari)) }} s/d {{ date('d/m/Y', strtotime($sampai)) }}
                @if ($jenis) | Klasifikasi: {{ $jenis }} @endif
            </p>
        </div>

        <!-- Ringkasan Statistik Rekapitulasi (Format Baris Formal) -->
        <div class="font-sans text-xs border border-slate-900 p-2.5 bg-slate-50 flex items-center justify-around text-center">
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-600 block">Surat Masuk</span>
                <span class="text-sm font-extrabold text-slate-900">{{ $totalSM }}</span>
            </div>
            <div class="border-l border-slate-300 h-6"></div>
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-600 block">Surat Keluar</span>
                <span class="text-sm font-extrabold text-slate-900">{{ $totalSK }}</span>
            </div>
            <div class="border-l border-slate-300 h-6"></div>
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-600 block">Arsip Digital</span>
                <span class="text-sm font-extrabold text-slate-900">{{ $totalAD }}</span>
            </div>
            <div class="border-l border-slate-300 h-6"></div>
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-600 block">Total Rekapitulasi</span>
                <span class="text-sm font-extrabold text-slate-900">{{ $totalSM + $totalSK + $totalAD }}</span>
            </div>
        </div>

        <!-- Tabel Data Rekapitulasi Formal Kedinasan -->
        <table class="w-full text-left text-xs border-collapse border border-slate-900 font-sans">
            <thead>
                <tr class="bg-slate-100 text-slate-900 font-bold uppercase border-b border-slate-900 text-[11px]">
                    <th scope="col" class="py-2 px-3 w-10 text-center border-r border-slate-900">NO</th>
                    <th scope="col" class="py-2 px-3 w-28 border-r border-slate-900">TANGGAL</th>
                    <th scope="col" class="py-2 px-3 w-44 border-r border-slate-900">NOMOR SURAT</th>
                    <th scope="col" class="py-2 px-3 border-r border-slate-900">PERIHAL / JUDUL NASKAH</th>
                    <th scope="col" class="py-2 px-3 w-32 text-center">KLASIFIKASI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-300 text-slate-900">
                @forelse ($rekapitulasi as $idx => $row)
                    <tr class="border-b border-slate-300">
                        <td class="py-2 px-3 text-center border-r border-slate-300 font-mono text-[11px]">{{ $idx + 1 }}</td>
                        <td class="py-2 px-3 border-r border-slate-300 whitespace-nowrap">{{ $row['tanggal'] }}</td>
                        <td class="py-2 px-3 font-bold border-r border-slate-300 font-mono text-[11px]">{{ $row['nomor_surat'] }}</td>
                        <td class="py-2 px-3 border-r border-slate-300 leading-snug">{{ $row['perihal'] }}</td>
                        <td class="py-2 px-3 text-center font-bold text-[10px] uppercase tracking-wider">{{ $row['status'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500 italic">
                            Tidak ada data naskah dinas atau dokumen kearsipan dalam periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tanda Tangan Pengesahan Kedinasan (Sesuai Standar Tata Usaha) -->
        <div class="pt-6 flex justify-end font-sans">
            <div class="text-center w-64 space-y-1">
                <p class="text-slate-700 text-xs">{{ $pengaturan->kota_penerbitan ?? 'Semarang' }}, {{ date('d F Y') }}</p>
                <p class="font-bold text-slate-900 text-xs">Kepala Biro Tata Usaha &amp; Kearsipan</p>
                <div class="h-16"></div>
                <p class="font-bold text-slate-900 underline text-xs uppercase">{{ auth()->user()->name ?? 'Administrator Sistem' }}</p>
                <p class="text-slate-500 text-[10px]">NIP. 197805192003122001</p>
            </div>
        </div>

        <!-- Catatan Kaki Footer Resmi -->
        @if ($pengaturan->catatan_footer)
            <div class="border-t border-slate-300 pt-3 text-[10px] text-slate-500 text-center font-sans italic">
                {{ $pengaturan->catatan_footer }}
            </div>
        @endif
    </div>

</body>
</html>
