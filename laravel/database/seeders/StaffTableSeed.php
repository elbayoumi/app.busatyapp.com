<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🧑‍💼 Create Super Admin Staff
        $staff = Staff::create([
            'name'     => 'John Doe',
            'email'    => 'admin@busaty.com',
            'phone'    => '01020472050',
            'password' => Hash::make('Cdb@$5Fjy'),
        ]);

        // 🚌 Create trip types
        $staff->createdTripTypes()->createMany([
            [
                'name'        => 'رحلة الصباح',
                'description' => 'رحلة مخصصة للأنشطة الصباحية.',
            ],
            [
                'name'        => 'رحلة المساء',
                'description' => 'رحلة مخصصة للأنشطة المسائية.',
            ],
        ]);

        // 🎖 Assign role
        $staff->assignRole('Super Admin');

        // 💬 Insert static messages
        $staticMessages = [
            ['id' => 2,  'message' => 'بداية الرحلة'],
            ['id' => 3,  'message' => 'متبقي علي وصل الطالب خمس دقائق'],
            ['id' => 4,  'message' => 'إنتهاء الرحلة'],
            ['id' => 5,  'message' => 'لم تنتهي الرحلة بعد'],
            ['id' => 6,  'message' => 'الطالب غائب اليوم'],
            ['id' => 7,  'message' => 'لم ينتهي اليوم الدراسي بعد'],
            ['id' => 8,  'message' => 'الباص تحرك من المدرسة لتوصيل'],
            ['id' => 9,  'message' => 'الباص وصل مدرسة'],
            ['id' => 10, 'message' => 'الباص وصل امام المنزل لتوصيل'],
            ['id' => 11, 'message' => 'الباص بالقرب من المنزل لتوصيل'],
        ];

        DB::table('static_messages')->insert($staticMessages);
    }
}
