<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;

/**
 * Maps a user role to the Blade layout it must be rendered in,
 * so views shared between roles (profile, depot details) stay in one place.
 */
final class RoleLayout
{
    public static function for(User $user): string
    {
        return match ($user->role) {
            User::ROLE_SUPERVISOR => 'layouts.appsuperviseur',
            User::ROLE_MANAGER => 'layouts.appgerant',
            default => 'layouts.app2',
        };
    }
}
