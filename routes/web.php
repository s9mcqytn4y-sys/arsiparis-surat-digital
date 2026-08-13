<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Redirect root to Filament Admin Panel
Route::get('/', function () {
    return redirect('/admin');
});

// Fix Route [login] not defined exception
Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');
