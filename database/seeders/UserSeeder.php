<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            'name' => 'dumdum',
            'email' => 'dumdum@gmail.com',
            'password' => Hash::make('password'),
        ]);

        \DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'adminku@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
