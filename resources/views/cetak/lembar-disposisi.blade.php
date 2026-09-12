<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Disposisi - {{ $surat->nomor_agenda }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm 15mm 20mm;
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
            font-size: 11pt;
            line-height: 1.4;
        }
        .header-kop {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header-kop h2 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-kop h1 {
            font-size: 15pt;
            font-weight: bold;
            margin: 2px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-kop p {
            font-size: 9pt;
            margin: 2px 0 0 0;
            font-style: italic;
        }
        .title-doc {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            text-decoration: underline;
            margin: 14px 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .title-sub {
            text-align: center;
            font-size: 10pt;
            margin-bottom: 14px;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .table-data td, .table-data th {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            font-size: 10.5pt;
        }
        .bg-gray {
            background-color: #f3f4f6;
        }
        .checkbox-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .checkbox-item {
            display: inline-flex;
            align-items: center;
            margin-right: 15px;
        }
        .box-check {
            display: inline-block;
            width: 13px;
            height: 13px;
            border: 1.5px solid #000;
            margin-right: 5px;
            text-align: center;
            line-height: 11px;
            font-size: 10px;
            font-weight: bold;
        }
        .instruction-box {
            min-height: 140px;
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 15px;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .signature-table td {
            border: none;
            width: 50%;
            vertical-align: top;
            font-size: 10.5pt;
        }
        .signature-space {
            height: 65px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: none;
            }
        }
        .print-bar {
            background: #1e293b;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-family: sans-serif;
            font-size: 13px;
        }
        .print-btn {
            background: #047857;
            color: #fff;
            border: none;
            padding: 6px 14px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="print-bar no-print">
    <div><strong>Pratinjau Lembar Disposisi Naskah Dinas:</strong> {{ $surat->nomor_agenda }}</div>
    <div style="display: flex; gap: 10px;">
        <button onclick="window.print()" class="print-btn">Cetak Dokumen (A4)</button>
        <button onclick="window.close()" style="background: #475569; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">Tutup</button>
    </div>
</div>

<div class="header-kop">
    <h2>KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h2>
    <h1>UNIVERSITAS KAMPUS DIGITAL INDONESIA</h1>
    <h2 style="font-size: 11pt; font-weight: normal; margin-top: 2px;">{{ $surat->unitKerja?->nama_unit ?? 'SEKRETARIAT REKTORAT' }}</h2>
    <p>Jalan Kampus Digital No. 01, Kota Pendidikan - Indonesia | Laman: www.univ.ac.id | Pos-el: tatausaha@univ.ac.id</p>
</div>

<div class="title-doc">LEMBAR DISPOSISI NASKAH DINAS</div>
<div class="title-sub">Nomor Agenda Register: <strong>{{ $surat->nomor_agenda }}</strong></div>

<table class="table-data">
    <tr>
        <td style="width: 22%;" class="bg-gray"><strong>Surat Dari</strong></td>
        <td style="width: 43%;">{{ $surat->pengirim }}</td>
        <td style="width: 17%;" class="bg-gray"><strong>Tgl. Diterima</strong></td>
        <td style="width: 18%;">{{ $surat->tanggal_terima?->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td class="bg-gray"><strong>Nomor Surat</strong></td>
        <td>{{ $surat->nomor_surat }}</td>
        <td class="bg-gray"><strong>Tgl. Surat</strong></td>
        <td>{{ $surat->tanggal_surat?->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td class="bg-gray"><strong>Perihal</strong></td>
        <td colspan="3"><strong>{{ $surat->perihal }}</strong></td>
    </tr>
    <tr>
        <td class="bg-gray"><strong>Sifat Naskah</strong></td>
        <td colspan="3">
            <span class="checkbox-item"><span class="box-check"></span> Sangat Segera</span>
            <span class="checkbox-item"><span class="box-check">&#10003;</span> Segera</span>
            <span class="checkbox-item"><span class="box-check"></span> Penting / Rahasia</span>
            <span class="checkbox-item"><span class="box-check"></span> Biasa</span>
        </td>
    </tr>
</table>

<table class="table-data" style="margin-top: -1px;">
    <tr>
        <td style="width: 50%;" class="bg-gray"><strong>DISPOSISI DITERUSKAN KEPADA:</strong></td>
        <td style="width: 50%;" class="bg-gray"><strong>PETUNJUK / INSTRUKSI PIMPINAN:</strong></td>
    </tr>
    <tr>
        <td style="height: 220px;">
            <div style="font-weight: bold; color: #047857; margin-bottom: 8px;">
                @if ($surat->disposisiPegawai)
                    &#9654; {{ $surat->disposisiPegawai->nama_lengkap }} ({{ $surat->disposisiPegawai->jabatan }})
                @else
                    (Belum diteruskan ke pejabat spesifik)
                @endif
            </div>
            <div style="font-size: 9.5pt; color: #374151; margin-top: 10px; line-height: 1.6;">
                <div><span class="box-check"></span> Dekan / Wakil Dekan</div>
                <div><span class="box-check"></span> Ketua Program Studi</div>
                <div><span class="box-check"></span> Kepala Lembaga (LPPM / LPM)</div>
                <div><span class="box-check"></span> Kepala Biro Administrasi Umum & Akademik</div>
                <div><span class="box-check"></span> Pejabat Pembuat Komitmen (PPK)</div>
                <div><span class="box-check"></span> Koordinator Tata Usaha</div>
            </div>
        </td>
        <td>
            <div style="font-size: 9.5pt; color: #374151; margin-bottom: 12px; line-height: 1.6;">
                <div><span class="box-check"></span> Mohon pertimbangan / telaah staf</div>
                <div><span class="box-check"></span> Tindak lanjuti sesuai prosedur resmi</div>
                <div><span class="box-check"></span> Siapkan draf naskah jawaban</div>
                <div><span class="box-check"></span> Koordinasikan dengan unit kerja terkait</div>
                <div><span class="box-check"></span> Hadiri / Wakili pimpinan</div>
                <div><span class="box-check"></span> Arsipkan / Simpan sebagai referensi</div>
            </div>
            <div style="border-top: 1px dashed #6b7280; padding-top: 8px;">
                <strong>Catatan Khusus Pimpinan:</strong>
                <p style="margin: 4px 0 0 0; font-style: italic; font-size: 10pt;">
                    {{ $surat->instruksi_disposisi ?? '— Tidak ada catatan khusus tambahan —' }}
                </p>
            </div>
        </td>
    </tr>
</table>

<table class="signature-table">
    <tr>
        <td></td>
        <td style="text-align: center;">
            <div>Kota Pendidikan, {{ $surat->tanggal_terima?->translatedFormat('d F Y') ?? date('d F Y') }}</div>
            <div style="font-weight: bold;">Pimpinan Unit / Rektorat,</div>
            <div class="signature-space"></div>
            <div style="font-weight: bold; text-decoration: underline;">Prof. Dr. Ir. H. Arsiparis Pratama, M.Kom.</div>
            <div style="font-size: 9pt;">NIP. 19780512 200312 1 002</div>
        </td>
    </tr>
</table>

</body>
</html>
