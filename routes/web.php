<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CetakBukuAgendaController;
use App\Http\Controllers\CetakDisposisiController;
use App\Http\Controllers\DocumentStreamController;
use App\Livewire\SuratKeluar;
use App\Livewire\SuratMasuk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Beranda pengalihan
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Autentikasi Kedinasan
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Portal Tata Usaha & Kearsipan (Wajib Terautentikasi)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Naskah Masuk
    Route::get('/surat-masuk', SuratMasuk\Index::class)->name('surat-masuk.index');
    Route::get('/surat-masuk/buat', SuratMasuk\Create::class)->name('surat-masuk.create');
    Route::get('/surat-masuk/cetak-agenda', CetakBukuAgendaController::class)->name('cetak.buku-agenda');
    Route::get('/cetak/disposisi/{surat}', CetakDisposisiController::class)->name('cetak.disposisi');

    // Naskah Keluar
    Route::get('/surat-keluar', SuratKeluar\Index::class)->name('surat-keluar.index');
    Route::get('/surat-keluar/buat', SuratKeluar\Create::class)->name('surat-keluar.create');

    // Streaming Dokumen Terproteksi (Signed URL Anti-IDOR 15 Menit)
    Route::get('/documents/stream/{document}', [DocumentStreamController::class, 'stream'])
        ->name('documents.stream')
        ->middleware('signed');
});
