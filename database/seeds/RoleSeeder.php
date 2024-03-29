<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'super admin',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'cashier',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'members',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'finance',
            'guard_name' => 'web'
        ]);
    }
}
