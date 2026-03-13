<?php

namespace Tests\Traits;

use App\Models\User;
use Spatie\Permission\Models\Role;

trait CreatesUsers
{
    protected function createUser(): User
    {
        $this->ensureRolesExist();
        $user = User::factory()->create();
        $user->assignRole('user');
        return $user;
    }

    protected function createAdmin(): User
    {
        $this->ensureRolesExist();
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        return $admin;
    }

    protected function ensureRolesExist(): void
    {
        Role::firstOrCreate(['name' => 'user',  'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    }
}
