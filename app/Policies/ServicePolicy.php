<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isServiceProvider() || $user->isAdmin();
    }

    public function view(User $user, Service $service): bool
    {
        return $user->isAdmin() || $service->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isServiceProvider() && $user->is_active;
    }

    public function update(User $user, Service $service): bool
    {
        return ($user->isServiceProvider() && $service->user_id === $user->id && $user->is_active) 
            || $user->isAdmin();
    }

    public function delete(User $user, Service $service): bool
    {
        return ($user->isServiceProvider() && $service->user_id === $user->id && $user->is_active) 
            || $user->isAdmin();
    }
}