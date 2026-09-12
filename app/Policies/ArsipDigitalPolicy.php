<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ArsipDigital;
use App\Models\User;

final class ArsipDigitalPolicy
{
    /**
     * BOLA / IDOR Defense: Verifikasi kepemilikan dan batas wewenang unit kerja.
     */
    public function view(User $user, ArsipDigital $arsip): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->unit_kerja_id !== null && $user->unit_kerja_id === $arsip->unit_kerja_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'petugas_tu']);
    }

    public function delete(User $user, ArsipDigital $arsip): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasRole('petugas_tu') && $user->unit_kerja_id === $arsip->unit_kerja_id;
    }
}
