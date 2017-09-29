<?php

use Illuminate\Database\Seeder;
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
        DB::table('users')->insert([
                    'name' => 'jack',
                    'email' => 'jack.huynh.1201@gmail.com',
                    'password' => Hash::make('123abcABCD'),
                    'website' => 'http://localhost/wp/'
                ]);
    }
}
