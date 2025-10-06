<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grades')->insert([
            [
                'name' => 'Primary',
                'notes' => null,
                'classrooms_en' => json_encode(["First primary", "Second primary", "Third primary", "Fourth primary", "Fifth primary", "Sixth primary"]),
                'order' => 2,
                'classrooms_ar' => json_encode(["أول ابتدائي", "ثاني ابتدائي", "ثالث ابتدائي", "رابع ابتدائي", "خامس ابتدائي", "سادس ابتدائي"]),
            ],
            [
                'name' => 'Middle',
                'notes' => null,
                'classrooms_en' => json_encode(["First middle school", "Second middle school", "Third middle school"]),
                'order' => 3,
                'classrooms_ar' => json_encode(["أول متوسط", "ثاني متوسط", "ثالث متوسط"]),
            ],
            [
                'name' => 'secondary',
                'notes' => null,
                'classrooms_en' => json_encode(["First Secondary", "Second Secondary", "Third Secondary"]),
                'order' => 4,
                'classrooms_ar' => json_encode(["أول ثانوي", "ثاني ثانوي", "ثالث ثانوي"]),
            ],
            [
                'name' => 'KG',
                'notes' => null,
                'classrooms_en' => json_encode(["kg1", "kg2"]),
                'order' => 1,
                'classrooms_ar' => json_encode(["أول روضة", "ثاني روضة"]),
            ],
        ]);
    }
}
