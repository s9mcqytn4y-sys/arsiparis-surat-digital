<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ArsipDigital;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class DocumentStreamController extends Controller
{
    /**
     * Mengalirkan (stream) berkas naskah dinas dengan verifikasi Signed URL & Otorisasi Unit.
     */
    public function stream(Request $request, string $document): StreamedResponse
    {
        // 1. Cari model yang sesuai berdasarkan ID UUID
        $model = SuratMasuk::find($document)
            ?? SuratKeluar::find($document)
            ?? ArsipDigital::findOrFail($document);

        // 2. Otorisasi Kepemilikan (Anti-BOLA / Anti-IDOR)
        Gate::authorize('view', $model);

        $filePath = $model->file_path;
        if (empty($filePath) || ! Storage::disk('local')->exists($filePath)) {
            abort(404, 'Berkas naskah dinas tidak ditemukan dalam repositori privat.');
        }

        $fileName = basename($filePath);
        $fileSize = Storage::disk('local')->size($filePath);

        return Storage::disk('local')->response(
            $filePath,
            $fileName,
            [
                'Content-Type' => 'application/pdf',
                'Content-Length' => (string) $fileSize,
                'Content-Disposition' => 'inline; filename="'.$fileName.'"',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]
        );
    }

    /**
     * Mengunduh berkas naskah dinas fisik secara aman.
     */
    public function download(Request $request, string $document): StreamedResponse
    {
        $model = SuratMasuk::find($document)
            ?? SuratKeluar::find($document)
            ?? ArsipDigital::findOrFail($document);

        Gate::authorize('view', $model);

        $filePath = $model->file_path;
        if (empty($filePath) || ! Storage::disk('local')->exists($filePath)) {
            abort(404, 'Berkas naskah dinas tidak ditemukan dalam repositori privat.');
        }

        $fileName = basename($filePath);
        $fileSize = Storage::disk('local')->size($filePath);

        return Storage::disk('local')->response(
            $filePath,
            $fileName,
            [
                'Content-Type' => 'application/pdf',
                'Content-Length' => (string) $fileSize,
                'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }
}
