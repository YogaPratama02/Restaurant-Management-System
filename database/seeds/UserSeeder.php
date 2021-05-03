<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'phone_number' => '081381517194',
            'password' => bcrypt('admin12345'),
        ]);
        $admin->assignRole('super admin');
        DB::table('categories')->insert([
            [
                'name' => 'Breakfast'
            ],
            [
                'name' => 'Beverage'
            ],
            [
                'name' => 'Lunch'
            ],
            [
                'name' => 'Drinks'
            ],
            [
                'name' => 'Dinner'
            ],
            [
                'name' => 'Fruits'
            ],
            [
                'name' => 'Snacks'
            ]
        ]);

        $faker = Faker::create('id_ID');
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
        DB::table('inventories')->insert([
            'ingredients' => 'Sasa',
            'stock_quantity' => 100000,
            'alert_quantity' => 1000,
            'unit' => 'gram'
        ]);
        $voucher = DB::table('vouchers')->insert([
            'name' => 'super hemat',
            'discount' => 10,
            'status' => 'Active'
        ]);
        for ($i = 1; $i <= 100; $i++) {
            $category = $faker->numberBetween(1, 7);
            $menu = $faker->numberBetween(1, 50);
            $role = $faker->numberBetween(1, 4);
            $user = DB::table('users')->insertGetId([
                'name' => $faker->name(),
                'email' => $faker->unique()->email,
                'phone_number' => $faker->phoneNumber(),
                'password' => bcrypt('secret12345')
            ]);
            DB::table('model_has_roles')->insert([
                'role_id' => $role,
                'model_type' => 'App\User',
                'model_id' => $user,
            ]);

            $id = DB::table('menus')->insertGetId([
                'name' => $faker->foodName(),
                'hpp' => $faker->numberBetween(15000, 35000),
                'price' => $faker->numberBetween(20000, 50000),
                'image' => $faker->imageUrl($width = 120, $height = 120),
                'discount' => $faker->numberBetween(1, 10),
                'category_id' => $category
            ]);

            $table = DB::table('tables')->insertGetId([
                'name' => "A$i"
            ]);

            DB::table('inventory_menus')->insert([
                'inventory_id' => 1,
                'menu_id' => $id,
                'consumption' => 100
            ]);

            $sale = DB::table('sales')->insertGetId([
                'table_id' => $table,
                'user_id' => $user,
                'voucher_id' => $voucher,
                'customer_name' => $faker->name(),
                'customer_phone' => $faker->phoneNumber(),
                'total_hpp' => $faker->numberBetween(30000, 50000),
                'total_price' => $faker->numberBetween(50000, 80000),
                'total_vat' => $faker->numberBetween(1, 10),
                'total_vatprice' => $faker->numberBetween(80000, 95000),
                'total_received' => $faker->numberBetween(100000, 150000),
                'change' => $faker->numberBetween(5000, 15000),
                'payment_type' => $faker->randomElement(['cash', 'bank_transfer', 'payment_card']),
                'sale_status' => 'paid',
                'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 120 days', $timezone = null),
                'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 120 days', $timezone = null)
            ]);

            DB::table('sale_details')->insertGetId([
                'sale_id' => $sale,
                'menu_id' => $id,
                'menu_name' => $faker->foodName(),
                'menu_price' => $faker->numberBetween(40000, 60000),
                'quantity' => $faker->numberBetween(1, 3),
                'menu_discount' => $faker->numberBetween(2, 4),
                'note' => 'ayamnya banyakin',
                'status' => 'confirm',
                'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 120 days', $timezone = null),
                'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 120 days', $timezone = null)
            ]);

            DB::table('suppliers')->insert([
                'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 120 days', $timezone = null),
                'name' => $faker->company(),
                'total' => $faker->numberBetween(500000, 1000000),
                'user_id' => $user,
                'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 120 days', $timezone = null),
                'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 120 days', $timezone = null)
            ]);
        }
    }
}
