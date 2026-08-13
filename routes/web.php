<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Redirect root to Filament Admin Panel
Route::get('/', function () {
    return redirect('/admin');
});
