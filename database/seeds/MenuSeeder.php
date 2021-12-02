<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
        for ($i = 1; $i <= 100; $i++) {
            $category = $faker->numberBetween(1, 7);
            DB::table('menus')->insertGetId([
                'name' => $faker->foodName(),
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam, deserunt?',
                'hpp' => $faker->numberBetween(15000, 35000),
                'price' => $faker->numberBetween(20000, 50000),
                // 'image' => $faker->imageUrl(120, 120),
                'image' => 'uploads/menus/image_example.jpg',
                'discount' => $faker->numberBetween(1, 10),
                'category_id' => $category
            ]);
        }
    }
}
