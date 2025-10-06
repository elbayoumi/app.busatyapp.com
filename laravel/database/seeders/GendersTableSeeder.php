<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GendersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genders')->insert([
            [
                'id' => 1,
                'name' => 'ذكر',

            ],
            [
                'id' => 2,
                'name' => 'انثي',
            ]
        ]);
    }
}
