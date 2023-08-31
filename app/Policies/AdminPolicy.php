<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;

final class AdminPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('admins:view-any');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return $admin->can('admins:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, Admin $resource): bool
    {
        return $admin->can('admins:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Admin $resource): bool
    {
        return ! $resource->hasRole('Super Admin') && $admin->can('admins:delete');
    }
}
