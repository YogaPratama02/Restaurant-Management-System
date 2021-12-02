<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PPNSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ppns')->insert([
            'ppn' => 1
        ]);
    }
}
