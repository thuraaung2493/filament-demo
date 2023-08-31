<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use Spatie\Permission\Models\Role;

final class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('roles:view-any');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return $admin->can('roles:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, Role $role): bool
    {
        return $admin->can('roles:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Role $role): bool
    {
        return $admin->can('roles:delete');
    }
}
