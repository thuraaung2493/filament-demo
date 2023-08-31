<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

final class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::query()->firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'admin'
        ]);

        /** @var Admin */
        $admin = Admin::query()->updateOrCreate(
            [
                'name' => 'Thura Aung',
                'email' => 'thuraaung2493@gmail.com',
            ],
            ['password' => Hash::make('password'),]
        );

        $admin->assignRole($role);
    }
}
