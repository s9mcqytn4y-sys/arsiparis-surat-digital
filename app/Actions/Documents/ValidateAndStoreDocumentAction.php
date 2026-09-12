<?php

declare(strict_types=1);

namespace App\Actions\Documents;

use App\DTOs\StoredDocumentResult;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

final class ValidateAndStoreDocumentAction
{
    private const string PDF_MAGIC_BYTES = '%PDF';

    private const int MAX_FILE_SIZE_BYTES = 10485760; // 10 MB

    /**
     * Memvalidasi magic bytes (%PDF-) dan menyimpan berkas ke storage privat terproteksi.
     *
     * @throws InvalidArgumentException
     */
    public function execute(UploadedFile $file, string $subDirectory = 'documents'): StoredDocumentResult
    {
        // 1. Validasi Ukuran Berkas
        $fileSize = $file->getSize();
        if ($fileSize > self::MAX_FILE_SIZE_BYTES) {
            throw new InvalidArgumentException(
                'Ukuran berkas naskah dinas melebihi batas maksimum 10 Megabyte.'
            );
        }

        // 2. Validasi Ekstensi & MIME
        $clientExtension = strtolower($file->getClientOriginalExtension());
        if ($clientExtension !== 'pdf') {
            throw new InvalidArgumentException(
                'Hanya berkas format PDF (.pdf) yang diperkenankan untuk tata kelola naskah dinas.'
            );
        }

        // 3. Validasi Magic Bytes (%PDF-)
        $handle = fopen($file->getRealPath(), 'rb');
        if ($handle === false) {
            throw new InvalidArgumentException('Gagal membuka berkas untuk verifikasi integritas.');
        }

        $header = fread($handle, 4);
        fclose($handle);

        if ($header !== self::PDF_MAGIC_BYTES) {
            throw new InvalidArgumentException(
                'Berkas ditolak: Header biner berkas bukan format PDF yang sah (indikasi manipulasi ekstensi).'
            );
        }

        // 4. Hitung Checksum SHA-256
        $fileHash = hash_file('sha256', $file->getRealPath());
        if ($fileHash === false) {
            throw new InvalidArgumentException('Gagal menghitung nilai checksum berkas naskah.');
        }

        // 5. Simpan Berkas dengan Nama Acak UUID v7 di Storage Privat
        $randomFileName = Str::uuid()->toString().'.pdf';
        $targetPath = trim($subDirectory, '/').'/'.$randomFileName;

        $stored = Storage::disk('local')->putFileAs(
            $subDirectory,
            $file,
            $randomFileName
        );

        if ($stored === false) {
            throw new InvalidArgumentException('Gagal menyimpan berkas ke repositori privat.');
        }

        return new StoredDocumentResult(
            filePath: $targetPath,
            fileMime: 'application/pdf',
            fileSize: $fileSize,
            fileHash: $fileHash,
            originalName: $file->getClientOriginalName(),
        );
    }
}
