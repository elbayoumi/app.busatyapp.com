<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'John Merchants';
        $user->email = 'merchants@test.com';
        $user->phone = '01014836273';
        $user->password = Hash::make('12345678');
        $user->supervisor_id = '1';
        $user->account_type = 'merchants';
        $user->save();

        $user = new User;
        $user->name = 'John Distributors';
        $user->email = 'distributors@test.com';
        $user->phone = '01014836272';
        $user->password = Hash::make('12345678');
        $user->supervisor_id = '1';
        $user->account_type = 'distributors';
        $user->save();
    }
}
