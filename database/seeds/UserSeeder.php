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
        //cabang alam sutera
        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'pribadimy',
            'fullname' => 'Pribadi Muhammad Yulianto',
            'email' => 'muhammadpribadi1202@gmail.com',
            'password' => bcrypt('P@ssw0rd12345'),
            'phone_number' => '081223456789',
            'role' => 'admin',
            'branch_id' => 1,
            'status' => '1',
            'created_by' => 'budi saputri',
            'created_at' => '2020-12-30'
        ]);
    }
}