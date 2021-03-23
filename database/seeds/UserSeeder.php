<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $user = User::create([
        //     'name' => 'Yoga cashier',
        //     'email' => 'yogacas@gmail.com',
        //     'phone_number' => '08648322638',
        //     'password' => bcrypt('yoga12345')
        // ]);

        // $user->assignRole('cashier');
        // $permission = Permission::create(['name' => 'super admin']);
    }
}
