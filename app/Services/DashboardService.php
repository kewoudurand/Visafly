<?php

namespace App\Services;

use App\Models\User;
use App\Models\TcfPassage;

class DashboardService
{
    public function widgetsFor(User $user): array
    {
        $widgets = [];

        if ($user->can('pass test') || $user->hasRole('user'))
            $widgets[] = 'tests';

        if ($user->can('book consultation') || $user->hasRole('user'))
            $widgets[] = 'consultations';

        if ($user->can('apply program') || $user->hasRole('user'))
            $widgets[] = 'programs';

        if ($user->can('manage users') || $user->hasRole('admin'))
            $widgets[] = 'admin_users';

        if ($user->can('view analytics') || $user->hasRole('admin'))
            $widgets[] = 'analytics';

        if ($user->can('manage consultation') || $user->hasRole('admin'))
            $widgets[] = 'admin_consultations';

        return $widgets;
    }

    public function statsFor(User $user): array
    {
        $stats = [];

        if ($user->can('pass test') || $user->hasRole('user')) {
            $stats['tests_passes'] = TcfPassage::where('user_id', $user->id)
                ->where('statut', 'termine')->count();

            $stats['score'] = TcfPassage::where('user_id', $user->id)
                ->where('statut', 'termine')->avg('score') ?? 0;
        }

        return $stats;
    }
}