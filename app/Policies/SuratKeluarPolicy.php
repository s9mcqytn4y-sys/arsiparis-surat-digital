<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SuratKeluar;
use App\Models\User;

final class SuratKeluarPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'petugas_tu', 'pimpinan_unit']);
    }

    public function view(User $user, SuratKeluar $surat): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->unit_kerja_id !== null && $user->unit_kerja_id === $surat->unit_kerja_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'petugas_tu']);
    }

    public function update(User $user, SuratKeluar $surat): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasRole('petugas_tu') && $user->unit_kerja_id === $surat->unit_kerja_id;
    }

    public function delete(User $user, SuratKeluar $surat): bool
    {
        return $user->hasRole('super_admin');
    }
}
