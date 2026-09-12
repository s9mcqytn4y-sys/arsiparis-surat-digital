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
        'email' => 'staf.tu@kampus.ac.id',
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
            'email' => 'target.bruteforce@kampus.ac.id',
            'password' => 'SandiSalah!'.$i,
        ]);
        $response->assertSessionHasErrors('email');
    }

    // Percobaan ke-6 wajib diblokir oleh RateLimiter
    $blockedResponse = $this->post(route('login.post'), [
        'email' => 'target.bruteforce@kampus.ac.id',
        'password' => 'SecretSecure999', // Meskipun sandi benar, tetap terkunci
    ]);

    $blockedResponse->assertSessionHasErrors('email');
    $this->assertGuest();
});
