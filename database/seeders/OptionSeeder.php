<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('options')->insert([

                [
                    'option' => 'true',
                    'user_id' => 1,
                    'updated_by' => 1,
                ],
                [
                    'option' => 'false',
                    'user_id' => 1,
                    'updated_by' => 1,
                ]

            ]);
    }
}
