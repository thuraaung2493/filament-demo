<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'admins:view-any', 'admins:create', 'admins:update', 'admins:delete',
            'roles:view-any', 'roles:create', 'roles:update', 'roles:delete',
            'owners:view-any', 'owners:create', 'owners:update', 'owners:delete',
            'patients:view-any', 'patients:create', 'patients:update', 'patients:delete',
            'users:view-any', 'users:view', 'users:update', 'users:delete', 'users:force-delete', 'users:restore',
        ];

        DB::table('permissions')->insertOrIgnore(\collect($permissions)->map(function ($permission) {
            return [
                'name' => $permission,
                'guard_name' => 'admin',
                'created_at' => \now(),
                'updated_at' => \now(),
            ];
        })->toArray());
    }
}
