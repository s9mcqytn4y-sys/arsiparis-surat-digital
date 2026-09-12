<?php

declare(strict_types=1);

use App\Actions\Documents\ValidateAndStoreDocumentAction;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class);

test('validasi magic bytes PDF berhasil menyimpan berkas asli ke disk privat', function () {
    Storage::fake('local');

    $action = app(ValidateAndStoreDocumentAction::class);

    // File PDF riil diawali dengan magic bytes %PDF-
    $pdfContent = "%PDF-1.4\n1 0 obj\n<< /Title (Uji Dokumen Dinas) >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF";
    $file = UploadedFile::fake()->createWithContent('surat_keputusan.pdf', $pdfContent);

    $result = $action->execute($file, 'documents');

    expect($result->originalName)->toBe('surat_keputusan.pdf')
        ->and($result->fileMime)->toBe('application/pdf')
        ->and($result->fileHash)->toBe(hash('sha256', $pdfContent))
        ->and($result->filePath)->toStartWith('documents/')
        ->and($result->filePath)->toEndWith('.pdf');

    Storage::disk('local')->assertExists($result->filePath);
});

test('berkas berbahaya atau palsu yang hanya mengubah ekstensi ditolak oleh validasi magic bytes', function () {
    Storage::fake('local');

    $action = app(ValidateAndStoreDocumentAction::class);

    // Berkas palsu: ekstensi .pdf tetapi magic bytes bukan %PDF- (misal file text atau binary shell)
    $fakeContent = "MZ\x90\x00\x03\x00\x00\x00\x04\x00\x00\x00";
    $file = UploadedFile::fake()->createWithContent('exploit.pdf', $fakeContent);

    expect(fn () => $action->execute($file, 'documents'))
        ->toThrow(InvalidArgumentException::class);
});
