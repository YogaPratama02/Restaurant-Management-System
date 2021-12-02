<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        // $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));

        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 15 days', $timezone = null),
            'name' => 'Bawang Merah',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Bawang Putih',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Cabai',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => "Jahe",
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 3,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Kunyit',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => "Cengkeh",
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 3,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Daging Ayam',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 3,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Daging Sapi',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Merica',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 3,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Madu',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 1,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Cokelat Bubuk',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Gula Aren',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 3,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Espresso',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Kentang',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 3,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Beras',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Telur',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Tomat',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Mangga',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Apel',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);

        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Sirsak',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);

        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Jeruk',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);

        DB::table('suppliers')->insert([
            'date' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'name' => 'Pepaya',
            'total' => $faker->numberBetween(500000, 1000000),
            'user_id' => 2,
            'created_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null),
            'updated_at' => $faker->dateTimeInInterval($startDate = '0 years', $interval = '- 5days', $timezone = null)
        ]);
    }
}
