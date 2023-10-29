<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => "Administrator",
                'email' => "admin@gmail.com",
                'password' => Hash::make('password')
            ],
        ]);

        DB::table('user_link_roles')->insert([
            [
                'user_id' => 1,
                'role_id' => 4,
            ],
        ]);


    }
}
