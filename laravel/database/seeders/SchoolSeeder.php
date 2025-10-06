<?php

namespace Database\Seeders;

use App\Models\Attendant;
use App\Models\bus;
use App\Models\Classroom;
use App\Models\Gender;
use App\Models\My_Parent;
use App\Models\Religion;
use App\Models\School;
use App\Models\Student;
use App\Models\Type_Blood;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


      $user1 =  School::create([
        'name' => 'مدارس النيل',
        'email' => 'busaty_test@gmail.com',
        'phone' => '02345995759',
        'email_verified_at' => 1,
        'password' => bcrypt('123456789'),
        'address' => 'المنصورة',
        'city_name' => 'المنصور',
        'latitude' => '32',
        'longitude' => '32',

        ]);




        //     $user1->grades()->sync([1,2,3,4]);




        // Classroom::create([
        //     'name' => "First primary",
        //     'school_id' => 1,
        //     'grade_id' => 1,

        // ]);

        // Classroom::create([
        //     'name' => "Second primary",
        //     'school_id' => 1,
        //     'grade_id' => 1,

        // ]);

        // Classroom::create([
        //     'name' => "Third primary",
        //     'school_id' => 1,
        //     'grade_id' => 1,

        // ]);

        // Classroom::create([
        //     'name' => "Fourth primary",
        //     'school_id' => 1,
        //     'grade_id' => 1,

        // ]);

        // Classroom::create([
        //     'name' => "Fifth primary",
        //     'school_id' => 1,
        //     'grade_id' => 1,

        // ]);


        // Classroom::create([
        //     'name' => "Sixth primary",
        //     'school_id' => 1,
        //     'grade_id' => 1,

        // ]);

        // Classroom::create([
        //     'name' => "First middle school",
        //     'school_id' => 1,
        //     'grade_id' => 2,

        // ]);

        // Classroom::create([
        //     'name' => "Second middle school",
        //     'school_id' => 1,
        //     'grade_id' => 2,

        // ]);

        // Classroom::create([
        //     'name' => "Third middle school",
        //     'school_id' => 1,
        //     'grade_id' => 2,

        // ]);


        // // ["third secondary","second secondary","first secondary"]

        // Classroom::create([
        //     'name' => "first secondary",
        //     'school_id' => 1,
        //     'grade_id' => 3,

        // ]);

        // Classroom::create([
        //     'name' => "second secondary",
        //     'school_id' => 1,
        //     'grade_id' => 3,

        // ]);

        // Classroom::create([
        //     'name' => "third secondary",
        //     'school_id' => 1,
        //     'grade_id' => 3,

        // ]);






        // Classroom::create([
        //     'name' => 'kg1',
        //     'school_id' => 1,
        //     'grade_id' => 4,

        // ]);


        // Classroom::create([
        //     'name' => 'kg2',
        //     'school_id' => 1,
        //     'grade_id' => 4,

        // ]);



        // $bus1 = bus::create([
        //     'name' => 'باص النيل',
        //     'school_id' => 1,
        //     'car_number' => 417,

        // ]);

        // $bus2 = bus::create([
        //     'name' => 'باص المنصورة',
        //     'school_id' => 1,
        //     'car_number' => 417,

        // ]);





    //     $attendant1 = Attendant::create([
    //         'name' => 'admin1',
    //         'school_id' => 1,
    //         'gender_id' => 1,
    //         'religion_id'=> 1,
    //         'type__blood_id' => 1,
    //         'Joining_Date' => date('Y-m-d'),
    //         'address'=> 'المنصورة',
    //         'city_name' => 'المنصورة',
    //         'type' => 'admins',
    //         'phone'=> '014146648675',
    //         'birth_date' => date('Y-m-d'),
    //         'email_verified_at' => 1,
    //         'password' => bcrypt('123456789'),
    //         'bus_id' => 1,
    //         'username' => 'admin1',


    //     ]);


    //     $attendant2 = Attendant::create([
    //         'name' => 'admin2',
    //         'school_id' => 1,
    //         'gender_id' => 1,
    //         'religion_id'=> 1,
    //         'type__blood_id' => 1,
    //         'Joining_Date' => date('Y-m-d'),
    //         'address'=> 'المنصورة',
    //         'city_name' => 'المنصورة',
    //         'type' => 'admins',
    //         'phone'=> '014146648575',
    //         'birth_date' => date('Y-m-d'),
    //         'email_verified_at' => 1,
    //         'password' => bcrypt('123456789'),
    //         'bus_id' => 2,
    //         'username' => 'admin2',

    //     ]);


    //     $attendant3 = Attendant::create([
    //         'name' => 'drivers1',
    //         'school_id' => 1,
    //         'gender_id' => 1,
    //         'religion_id'=> 1,
    //         'type__blood_id' => 1,
    //         'Joining_Date' => date('Y-m-d'),
    //         'address'=> 'المنصورة',
    //         'city_name' => 'المنصورة',
    //         'type' => 'drivers',
    //         'phone'=> '014147664875',
    //         'birth_date' => date('Y-m-d'),
    //         'email_verified_at' => 1,
    //         'password' => bcrypt('123456789'),
    //         'bus_id' => 1,
    //         'username' => 'drivers1',

    //     ]);

    //     $attendant4 = Attendant::create([
    //         'name' => 'drivers2',
    //         'school_id' => 1,
    //         'gender_id' => 1,
    //         'religion_id'=> 1,
    //         'type__blood_id' => 1,
    //         'Joining_Date' => date('Y-m-d'),
    //         'address'=> 'المنصورة',
    //         'city_name' => 'المنصورة',
    //         'type' => 'drivers',
    //         'phone'=> '014146694875',
    //         'birth_date' => date('Y-m-d'),
    //         'email_verified_at' => 1,
    //         'password' => bcrypt('123456789'),
    //         'bus_id' => 2,
    //         'username' => 'drivers2',


    //     ]);


    //     $classroom1_ids = [1,2,3,4,5,6];
    //     $trip_type =['full_day', 'end_day', 'start_day'];
    //     for ($i=1; $i <= 15; $i++) {
    //         Student::create([
    //             'name' => 'student' . $i,
    //             'school_id' => 1,
    //             'gender_id' => Gender::all()->random()->id,
    //             'religion_id'=> Religion::all()->random()->id,
    //             'type__blood_id' => Type_Blood::all()->random()->id,
    //             'address'=> 'المنصورة',
    //             'city_name' => 'المنصورة',
    //             'phone'=> rand(100000,1000000000),
    //             'Date_Birth' => date('Y-m-d'),
    //             'bus_id' => bus::all()->random()->id,
    //             'grade_id' => 1,
    //             'classroom_id' => $classroom1_ids[rand(0,5)],
    //             'trip_type' => $trip_type[rand(0,2)],
    //             'parent_key' => rand(1000,100000),
    //             'parent_secret' => rand(1000,100000),
    //             'latitude' => rand(10,100),
    //             'longitude' => rand(10,100),

    //         ]);
    //     }


    //     $classroom2_ids = [7,8,9];
    //     for ($i=1; $i <= 15; $i++) {
    //         Student::create([
    //             'name' => 'student' . 15 + $i,
    //             'school_id' => 1,
    //             'gender_id' => Gender::all()->random()->id,
    //             'religion_id'=> Religion::all()->random()->id,
    //             'type__blood_id' => Type_Blood::all()->random()->id,
    //             'address'=> 'المنصورة',
    //             'city_name' => 'المنصورة',
    //             'phone'=> rand(100000,1000000000),
    //             'Date_Birth' => date('Y-m-d'),
    //             'bus_id' => bus::all()->random()->id,
    //             'grade_id' => 2,
    //             'classroom_id' => $classroom2_ids[rand(0,2)],
    //             'trip_type' => $trip_type[rand(0,2)],
    //             'parent_key' => rand(1000,100000),
    //             'parent_secret' => rand(1000,100000),
    //             'latitude' => rand(10,100),
    //             'longitude' => rand(10,100),

    //         ]);
    //     }


    //     $classroom3_ids = [10,11,12];
    //     for ($i=1; $i <= 15; $i++) {
    //         Student::create([
    //             'name' => 'student' . 30 + $i,
    //             'school_id' => 1,
    //             'gender_id' => Gender::all()->random()->id,
    //             'religion_id'=> Religion::all()->random()->id,
    //             'type__blood_id' => Type_Blood::all()->random()->id,
    //             'address'=> 'المنصورة',
    //             'city_name' => 'المنصورة',
    //             'phone'=> rand(100000,1000000000),
    //             'Date_Birth' => date('Y-m-d'),
    //             'bus_id' => bus::all()->random()->id,
    //             'grade_id' => 3,
    //             'classroom_id' => $classroom3_ids[rand(0,2)],
    //             'trip_type' => $trip_type[rand(0,2)],
    //             'parent_key' => rand(1000,100000),
    //             'parent_secret' => rand(1000,100000),
    //             'latitude' => rand(10,100),
    //             'longitude' => rand(10,100),

    //         ]);
    //     }


    //     $classroom4_ids = [13,14];
    //     for ($i=1; $i <= 15; $i++) {
    //         Student::create([
    //             'name' => 'student' . 45 + $i,
    //             'school_id' => 1,
    //             'gender_id' => Gender::all()->random()->id,
    //             'religion_id'=> Religion::all()->random()->id,
    //             'type__blood_id' => Type_Blood::all()->random()->id,
    //             'address'=> 'المنصورة',
    //             'city_name' => 'المنصورة',
    //             'phone'=> rand(100000,1000000000),
    //             'Date_Birth' => date('Y-m-d'),
    //             'bus_id' => bus::all()->random()->id,
    //             'grade_id' => 4,
    //             'classroom_id' => $classroom4_ids[rand(0,1)],
    //             'trip_type' => $trip_type[rand(0,2)],
    //             'parent_key' => rand(1000,100000),
    //             'parent_secret' => rand(1000,100000),
    //             'latitude' => rand(10,100),
    //             'longitude' => rand(10,100),

    //         ]);



    //     }

    //     for ($i=1; $i <= 10; $i++) {

    //         $parent = My_Parent::create([
    //             'name' => 'parent'.$i,
    //             'email_verified_at' => 1,
    //             'email' => 'busaty_parent'.$i.'@gmail.com',
    //             'password' => bcrypt('123456789'),
    //             'address' => 'المنصورة',
    //             'phone' => rand(100000,1000000000),

    //         ]);

    //         $parent->students()->sync([Student::all()->random()->id,Student::all()->random()->id]);


    //     }

    }



}
