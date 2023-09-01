<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Filament\Tables\Actions\EditAction;

use App\Filament\Resources\UserResource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

use function Pest\Livewire\livewire;
use function Pest\Laravel\{actingAs, assertDatabaseCount, assertModelExists, get};

describe('Authenticated user with superadmin role', function (): void {

    beforeEach(function (): void {
        /** @var Admin */
        $admin = Admin::factory()->create();
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $admin->assignRole($role);
        actingAs($admin, 'admin');

        $this->users = User::factory(10)->create();
    });

    test('can render a list page', function (): void {
        get(UserResource::getUrl('index'))->assertSuccessful();
    });

    test('can list users', function (): void {
        livewire(UserResource\Pages\ListUsers::class)
            ->assertCanSeeTableRecords($this->users)
            ->assertCanRenderTableColumn('name')
            ->assertCanRenderTableColumn('email')
            ->assertCanRenderTableColumn('created_at')
            ->assertCountTableRecords(10);
    });

    test('can sort users by name and created date', function (): void {
        livewire(UserResource\Pages\ListUsers::class)
            ->sortTable('name')
            ->assertCanSeeTableRecords($this->users->sortBy('name'), inOrder: true)
            ->sortTable('name', 'desc')
            ->assertCanSeeTableRecords($this->users->sortByDesc('name'), inOrder: true)
            ->sortTable('created_at')
            ->assertCanSeeTableRecords($this->users->sortBy('created_at'), inOrder: true)
            ->sortTable('created_at', 'desc')
            ->assertCanSeeTableRecords($this->users->sortByDesc('created_at'), inOrder: true);
    });

    test('can search users by name and email', function (): void {
        $user = $this->users->first();

        livewire(UserResource\Pages\ListUsers::class)
            ->searchTable($user->name)
            ->assertCanSeeTableRecords($this->users->where('name', $user->name))
            ->assertCanNotSeeTableRecords($this->users->where('name', '!=', $user->name))
            ->searchTable($user->email)
            ->assertCanSeeTableRecords($this->users->where('email', $user->email))
            ->assertCanNotSeeTableRecords($this->users->where('email', '!=', $user->email));
    });

    test('can get the user created date with format "M j, Y"', function (): void {
        /** @var User */
        $user = $this->users->first();
        /** @var Carbon */
        $userCreated = $user->created_at;

        livewire(UserResource\Pages\ListUsers::class)
            ->assertTableColumnFormattedStateSet('created_at', $userCreated->format('M j, Y'), record: $user)
            ->assertTableColumnFormattedStateNotSet('created_at', $userCreated, record: $user);
    });

    it('can filter users by `verified`', function (): void {
        $users = $this->users;

        livewire(UserResource\Pages\ListUsers::class)
            ->assertCanSeeTableRecords($users)
            ->filterTable('email_verified_at', true)
            ->assertCanSeeTableRecords($users->whereNotNull('email_verified_at'))
            ->assertCanNotSeeTableRecords($users->whereNull('email_verified_at'))
            ->filterTable('email_verified_at', false)
            ->assertCanSeeTableRecords($users->whereNull('email_verified_at'))
            ->assertCanNotSeeTableRecords($users->whereNotNull('email_verified_at'));
    });

    test('cannot render a create page', function (): void {
        get(UserResource::getUrl('create'))->assertForbidden();
    });

    test('can view a user', function (): void {
        $user = $this->users->first();

        livewire(UserResource\Pages\ListUsers::class)
            ->callTableAction(ViewAction::class, $user)
            ->assertSuccessful()
            ->assertSee($user->name)
            ->assertSee($user->email)
            ->assertSee($user->created_at);
    });

    test('can delete users', function (): void {
        /** @var User */
        $user = $this->users->first();

        livewire(UserResource\Pages\EditUser::class, ['record' => $user->id])
            ->callAction(DeleteAction::class, ['id' => $user->getRouteKey()]);

        assertModelExists($user);
        expect($user->refresh())->deleted_at->not->toBeNull();
    });

    test('can bulk delete users', function (): void {
        $users = $this->users;

        livewire(UserResource\Pages\ListUsers::class)
            ->callTableBulkAction(DeleteBulkAction::class, $users);

        assertDatabaseCount('users', $users->count());
        foreach ($users as $user) {
            expect($user->refresh())->deleted_at->not->toBeNull();
        }
    });

    test('can edit users', function (): void {
        $user = $this->users->first();

        livewire(UserResource\Pages\ListUsers::class)
            ->callTableAction(EditAction::class, $user, data: [
                'name' => $name = fake()->userName(),
                'email' => $email = fake()->unique()->safeEmail(),
                'password' => $password = Hash::make('password'),
            ])
            ->assertHasNoTableActionErrors();

        expect($user->refresh())
            ->name->toBe($name)
            ->email->toBe($email)
            ->password->toBe($password);
    });
});

describe('Authenticated user without permission', function (): void {

    beforeEach(function (): void {
        /** @var Admin */
        $admin = Admin::factory()->create();
        $this->role = Role::create(['name' => 'Test', 'guard_name' => 'admin']);
        $this->viewAnyPermission = Permission::create(['name' => 'users:view-any', 'guard_name' => 'admin']);

        $admin->assignRole($this->role);

        actingAs($admin, 'admin');
    });

    test('cannot render a list page', function (): void {
        get(UserResource::getUrl('index'))->assertForbidden();
    });

    test('cannot render a create page', function (): void {
        get(UserResource::getUrl('create'))->assertForbidden();
    });

    test('cannot render a view page', function (): void {
        $user = User::factory()->create();

        get(UserResource::getUrl('view', ['record' => $user->getRouteKey()]))
            ->assertForbidden();
    });

    test('cannot render a edit page', function (): void {
        $this->role->givePermissionTo($this->viewAnyPermission);

        $user = User::factory()->create();

        livewire(UserResource\Pages\ListUsers::class)
            ->assertSuccessful()
            ->assertActionDoesNotExist(EditAction::class);

        get(UserResource::getUrl('edit', ['record' => $user->getRouteKey()]))
            ->assertForbidden();
    });

    test('cannot delete a user', function (): void {
        $updatePermission = Permission::create(['name' => 'users:update', 'guard_name' => 'admin']);
        /** @var Role */
        $role = $this->role;
        $role->givePermissionTo([$this->viewAnyPermission, $updatePermission]);

        $user = User::factory()->create();

        livewire(UserResource\Pages\EditUser::class, ['record' => $user->getRouteKey()])
            ->assertSuccessful()
            ->assertActionHidden(DeleteAction::class);
    });
});
