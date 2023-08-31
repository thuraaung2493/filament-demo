<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;

final class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('users:view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $admin, User $model): bool
    {
        return $admin->can('users:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, User $model): bool
    {
        return $admin->can('users:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, User $model): bool
    {
        return $admin->can('users:delete');
    }
}
