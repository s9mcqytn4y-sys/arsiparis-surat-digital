<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Agenda Naskah Masuk - Periode {{ $startDate }} s.d. {{ $endDate }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 15mm 12mm 15mm;
        }
        * {
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
            color: #111827;
        }
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-size: 10pt;
            line-height: 1.3;
        }
        .header-kop {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .header-kop h2 {
            font-size: 12pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-kop h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 2px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-kop p {
            font-size: 8.5pt;
            margin: 1px 0 0 0;
            font-style: italic;
        }
        .title-doc {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
            margin: 10px 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .title-sub {
            text-align: center;
            font-size: 9.5pt;
            margin: 0 0 12px 0;
            color: #374151;
        }
        .table-agenda {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 9pt;
        }
        .table-agenda th {
            border: 1px solid #000000;
            background-color: #f3f4f6;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8.5pt;
        }
        .table-agenda td {
            border: 1px solid #000000;
            padding: 5px 6px;
            vertical-align: top;
        }
        .text-center {
            text-align: center;
        }
        .footer-sign {
            margin-top: 20px;
            width: 100%;
            page-break-inside: avoid;
        }
        .sign-box {
            float: right;
            width: 250px;
            text-align: center;
            font-size: 9.5pt;
        }
        .sign-space {
            height: 55px;
        }

        /* Action Toolbar untuk Web Preview */
        .no-print {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: ui-sans-serif, system-ui, sans-serif;
            font-size: 14px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-size: 13px;
        }
        .btn-print {
            background-color: #047857;
            color: #ffffff;
        }
        .btn-pdf {
            background-color: #1e293b;
            color: #ffffff;
        }
        .btn-close {
            background-color: #e2e8f0;
            color: #334155;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <div>
            <strong>Pratinjau Cetak: Buku Agenda Naskah Dinas Masuk</strong>
            <span style="color: #64748b; margin-left: 8px;">(Total: {{ $daftarSurat->count() }} naskah)</span>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="button" class="btn btn-print" onclick="window.print()">
                <span>🖨️ Cetak Dokumen</span>
            </button>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-pdf">
                <span>📄 Unduh PDF</span>
            </a>
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-close">
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <div style="padding: 10px 20px;">
        <div class="header-kop">
            <h2>KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h2>
            <h1>UNIVERSITAS DIGITAL INDONESIA</h1>
            <p>Jalan Kampus Digital Mandiri No. 01, Gd. Rektorat Lt. 2, Kotabaru | Posel: tu@kampus.ac.id | Laman: www.kampus.ac.id</p>
        </div>

        <div class="title-doc">BUKU AGENDA NASKAH DINAS MASUK</div>
        <div class="title-sub">
            Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}
            @if ($activeUnit)
                | Unit Kerja: <strong>{{ $activeUnit->nama_unit }} ({{ $activeUnit->kode_unit }})</strong>
            @else
                | Cakupan: <strong>Seluruh Unit Kerja Terpadu</strong>
            @endif
        </div>

        <table class="table-agenda">
            <thead>
                <tr>
                    <th style="width: 30px;">No.</th>
                    <th style="width: 120px;">No. Agenda</th>
                    <th style="width: 75px;">Tgl Terima</th>
                    <th style="width: 140px;">No. Naskah & Tgl</th>
                    <th style="width: 150px;">Asal Pengirim</th>
                    <th>Perihal & Ringkasan</th>
                    <th style="width: 110px;">Unit Pengelola</th>
                    <th style="width: 110px;">Disposisi Terakhir</th>
                    <th style="width: 75px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarSurat as $index => $surat)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td style="font-weight: bold; font-family: monospace; font-size: 8.5pt;">{{ $surat->nomor_agenda }}</td>
                        <td class="text-center">{{ $surat->tanggal_terima?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            <div style="font-weight: bold;">{{ $surat->nomor_surat }}</div>
                            <div style="font-size: 8pt; color: #4b5563;">Tgl: {{ $surat->tanggal_surat?->format('d/m/Y') ?? '-' }}</div>
                        </td>
                        <td>{{ $surat->pengirim }}</td>
                        <td>
                            <div>{{ $surat->perihal }}</div>
                            @if ($surat->catatan_disposisi)
                                <div style="margin-top: 3px; font-size: 8pt; color: #4b5563; font-style: italic;">
                                    Catatan: {{ $surat->catatan_disposisi }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $surat->unitKerja?->nama_unit ?? '-' }}</td>
                        <td>{{ $surat->disposisiPegawai?->nama_lengkap ?? '-' }}</td>
                        <td class="text-center" style="font-weight: bold; font-size: 8pt;">
                            {{ $surat->status_disposisi?->label() ?? $surat->status_disposisi?->value ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 20px; font-style: italic; color: #6b7280;">
                            Tidak ditemukan data naskah surat masuk untuk kriteria dan rentang tanggal yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer-sign">
            <div class="sign-box">
                <div>Dicetak pada: {{ now()->isoFormat('D MMMM Y, HH:mm') }} WIB</div>
                <div style="margin-top: 4px;">Kepala Bagian Tata Usaha & Kearsipan,</div>
                <div class="sign-space"></div>
                <div style="font-weight: bold; text-decoration: underline;">Dr. Ir. Hendra Prasetya, M.Kom.</div>
                <div style="font-size: 8.5pt;">NIP. 19780415 200312 1 002</div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

</body>
</html>
