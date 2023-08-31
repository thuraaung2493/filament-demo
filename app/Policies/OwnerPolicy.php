<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Owner;
use App\Models\User;

final class OwnerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('owners:view-any');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return $admin->can('owners:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, Owner $owner): bool
    {
        return $admin->can('owners:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Owner $owner): bool
    {
        return $admin->can('owners:delete');
    }
}
