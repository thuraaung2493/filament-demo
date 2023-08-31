<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Patient;
use App\Models\User;

final class PatientPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('patients:view-any');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return $admin->can('patients:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, Patient $patient): bool
    {
        return $admin->can('patients:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Patient $patient): bool
    {
        return $admin->can('patients:delete');
    }
}
