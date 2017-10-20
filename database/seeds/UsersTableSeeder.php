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
                    'name' => 'ni',
                    'email' => 'ninguyen1510@gmail.com',
                    'password' => Hash::make('diemthu1510'),
                    'website' => 'http://lamme.blog'
                ]);
    }
}
