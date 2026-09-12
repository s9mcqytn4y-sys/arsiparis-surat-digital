<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CetakBukuAgendaController;
use App\Http\Controllers\DocumentStreamController;
use App\Http\Controllers\LaporanCetakController;
use App\Http\Controllers\ProfileController;
use App\Livewire\ArsipDigital\Index;
use App\Livewire\Dashboard;
use App\Livewire\Pengaturan\Dokumen;
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
    Route::get('/dashboard', Dashboard\Index::class)->name('dashboard');

    // Profil Pengguna Kedinasan
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Naskah Masuk
    Route::get('/surat-masuk', SuratMasuk\Index::class)->name('surat-masuk.index');
    Route::get('/surat-masuk/buat', SuratMasuk\Create::class)->name('surat-masuk.create');
    Route::get('/surat-masuk/cetak-agenda', CetakBukuAgendaController::class)->name('cetak.buku-agenda');

    // Naskah Keluar
    Route::get('/surat-keluar', SuratKeluar\Index::class)->name('surat-keluar.index');
    Route::get('/surat-keluar/buat', SuratKeluar\Create::class)->name('surat-keluar.create');

    // Arsip Digital
    Route::get('/arsip-digital', Index::class)->name('arsip-digital.index');

    // Laporan & Analitik Kearsipan
    Route::get('/laporan', App\Livewire\Laporan\Index::class)->name('laporan.index');
    Route::get('/laporan/cetak', LaporanCetakController::class)->name('laporan.cetak');

    // Pengaturan & Data Master System
    Route::get('/pengaturan/dokumen', Dokumen::class)->name('pengaturan.dokumen');
    Route::get('/data-master', App\Livewire\DataMaster\Index::class)->name('data-master.index')->middleware('role:super_admin');

    // Streaming Dokumen Terproteksi (Signed URL Anti-IDOR 15 Menit) & Unduh Aman
    Route::get('/documents/stream/{document}', [DocumentStreamController::class, 'stream'])
        ->name('documents.stream')
        ->middleware('signed');

    Route::get('/documents/download/{document}', [DocumentStreamController::class, 'download'])
        ->name('documents.download')
        ->middleware('signed');
});
