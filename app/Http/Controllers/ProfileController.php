<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

final class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil pengguna.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $pegawai = $user ? Pegawai::where('email', $user->email)->first() : null;

        return view('profile.index', [
            'user' => $user,
            'pegawai' => $pegawai,
        ]);
    }

    /**
     * Perbarui informasi profil pengguna.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_if(! $user, 401);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat surel resmi wajib diisi.',
            'email.email' => 'Format alamat surel tidak valid.',
            'email.unique' => 'Alamat surel sudah digunakan oleh pengguna lain.',
            'avatar.image' => 'Berkas foto profil harus berupa gambar.',
            'avatar.mimes' => 'Format gambar yang diperbolehkan: JPEG, PNG, JPG, WEBP.',
            'avatar.max' => 'Ukuran gambar profil maksimal 2 Megabyte.',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            if ($avatarFile && $avatarFile->isValid()) {
                // Hapus avatar lama jika ada
                if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                    Storage::disk('public')->delete($user->avatar_path);
                }

                $filename = Str::uuid()->toString().'.'.$avatarFile->getClientOriginalExtension();
                $path = $avatarFile->storeAs('avatars', $filename, 'public');
                $updateData['avatar_path'] = $path;
            }
        }

        $user->update($updateData);

        // Sinkronisasi nama jika akun terhubung ke data pegawai
        if ($pegawai = Pegawai::where('email', $user->email)->first()) {
            $pegawai->update(['nama' => $validated['name']]);
        }

        return redirect()->route('profile.edit')->with('status', 'Informasi profil dan avatar kedinasan berhasil diperbarui.');
    }

    /**
     * Perbarui kata sandi pengguna.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_if(! $user, 401);

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini yang Anda masukkan tidak sesuai.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.edit')->with('status', 'Kata sandi akun kedinasan berhasil diperbarui.');
    }
}
