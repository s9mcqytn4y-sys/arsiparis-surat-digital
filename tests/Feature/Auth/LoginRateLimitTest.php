<?php

declare(strict_types=1);

use App\Models\User;

test('pengguna dapat masuk dengan kredensial yang sah dan sesi diregenerasi', function () {
    $user = User::factory()->create([
        'email' => 'staf.tu@kampus.ac.id',
        'password' => bcrypt('Password123!'),
        'is_active' => true,
    ]);

    $response = $this->post(route('login.post'), [
        'identity' => 'staf.tu@kampus.ac.id',
        'password' => 'Password123!',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('percobaan masuk gagal 5 kali berturut-turut memicu penguncian rate limiter', function () {
    $user = User::factory()->create([
        'email' => 'target.bruteforce@kampus.ac.id',
        'password' => bcrypt('SecretSecure999'),
        'is_active' => true,
    ]);

    // 5 percobaan dengan sandi keliru
    for ($i = 0; $i < 5; $i++) {
        $response = $this->post(route('login.post'), [
            'identity' => 'target.bruteforce@kampus.ac.id',
            'password' => 'SandiSalah!'.$i,
        ]);
        $response->assertSessionHasErrors('identity');
    }

    // Percobaan ke-6 wajib diblokir oleh RateLimiter
    $blockedResponse = $this->post(route('login.post'), [
        'identity' => 'target.bruteforce@kampus.ac.id',
        'password' => 'SecretSecure999', // Meskipun sandi benar, tetap terkunci
    ]);

    $blockedResponse->assertSessionHasErrors('identity');
    $this->assertGuest();
});

test('input remember bernilai on berhasil dinormalisasi menjadi boolean tanpa kendala validasi', function () {
    $user = User::factory()->create([
        'email' => 'arsiparis@kampus.ac.id',
        'password' => bcrypt('SandiAman123!'),
        'is_active' => true,
    ]);

    $response = $this->post(route('login.post'), [
        'identity' => 'arsiparis@kampus.ac.id',
        'password' => 'SandiAman123!',
        'remember' => 'on',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('masuk dengan identitas admin otomatis terpetakan dan validasi gagal menampilkan pesan bahasa indonesia', function () {
    // Validasi kosong
    $emptyResponse = $this->post(route('login.post'), [
        'identity' => '',
        'password' => '',
    ]);

    $emptyResponse->assertSessionHasErrors([
        'identity' => 'Identitas (username, surel, atau NIP) wajib diisi.',
        'password' => 'Kata sandi wajib diisi.',
    ]);

    // Kredensial salah menampilkan pesan kedinasan terpadu
    $invalidResponse = $this->post(route('login.post'), [
        'identity' => 'admin',
        'password' => 'SandiKeliru123',
    ]);

    $invalidResponse->assertSessionHasErrors([
        'identity' => 'Identitas atau kata sandi yang dimasukkan tidak valid.',
    ]);
});
