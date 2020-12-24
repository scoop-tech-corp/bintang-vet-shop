<?php

use Illuminate\Database\Seeder;

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
        	'staffing_number' => '12345',
        	'username' => 'budi',
        	'fullname' => 'budi saputri',
            'email' => 'budi@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456789',
            'role' =>'admin',
            'status' => 'active',
            'created_by' => 'budi'
        ]);

        DB::table('users')->insert([
        	'staffing_number' => '12345',
        	'username' => 'susi',
        	'fullname' => 'susi saputri',
            'email' => 'susi@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456788',
            'role' =>'resepsionis',
            'status' => 'active',
            'created_by' => 'budi'
        ]);

        DB::table('users')->insert([
        	'staffing_number' => '12345',
        	'username' => 'tono',
        	'fullname' => 'tono saputri',
            'email' => 'tono@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456787',
            'role' =>'dokter',
            'status' => 'active',
            'created_by' => 'budi'
        ]);
    }
}
