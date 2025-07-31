<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isDeveloper() || $user->isAdmin();
    }

    public function view(User $user, Property $property): bool
    {
        return $user->isAdmin() || $property->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isDeveloper() && $user->is_active;
    }

    public function update(User $user, Property $property): bool
    {
        return ($user->isDeveloper() && $property->user_id === $user->id && $user->is_active) 
            || $user->isAdmin();
    }

    public function delete(User $user, Property $property): bool
    {
        return ($user->isDeveloper() && $property->user_id === $user->id && $user->is_active) 
            || $user->isAdmin();
    }
}