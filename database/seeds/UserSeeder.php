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

        //branches
        DB::table('branches')->insert([
            'branch_code' => 'AS',
            'branch_name' => 'Alam Sutera',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);
        
        DB::table('branches')->insert([
            'branch_code' => 'KM',
            'branch_name' => 'Kembangan',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        DB::table('branches')->insert([
            'branch_code' => 'TJ',
            'branch_name' => 'Tanjung Duren',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        
        //cabang alam sutera
        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'budi',
            'fullname' => 'budi saputri',
            'email' => 'budi@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456789',
            'role' => 'admin',
            'branch_id' => 1,
            'status' => '1',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'susi',
            'fullname' => 'susi saputri',
            'email' => 'susi@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456788',
            'role' => 'resepsionis',
            'branch_id' => 1,
            'status' => '1',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'tono',
            'fullname' => 'tono saputri',
            'email' => 'tono@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456787',
            'role' => 'dokter',
            'branch_id' => 1,
            'status' => '1',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'tona',
            'fullname' => 'tona saputri',
            'email' => 'tona@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456888',
            'role' => 'dokter',
            'branch_id' => 1,
            'status' => '0',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        //cabang kembangan
        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'toto',
            'fullname' => 'toto saputri',
            'email' => 'toto@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456779',
            'role' => 'admin',
            'branch_id' => 2,
            'status' => '1',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'susan',
            'fullname' => 'susan saputri',
            'email' => 'susan@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456768',
            'role' => 'resepsionis',
            'branch_id' => 2,
            'status' => '1',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'deni',
            'fullname' => 'deni saputra',
            'email' => 'deni@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456757',
            'role' => 'dokter',
            'branch_id' => 2,
            'status' => '1',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'dena',
            'fullname' => 'dena saputra',
            'email' => 'dena@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456747',
            'role' => 'dokter',
            'branch_id' => 2,
            'status' => '0',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        //cabang tanjung duren
        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'tati',
            'fullname' => 'tati saputri',
            'email' => 'tati@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456749',
            'role' => 'admin',
            'branch_id' => 3,
            'status' => '1',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'sasa',
            'fullname' => 'sasa saputri',
            'email' => 'sasa@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456778',
            'role' => 'resepsionis',
            'branch_id' => 3,
            'status' => '1',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'desi',
            'fullname' => 'desi saputri',
            'email' => 'desi@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456777',
            'role' => 'dokter',
            'branch_id' => 3,
            'status' => '1',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);

        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'desna',
            'fullname' => 'desna saputri',
            'email' => 'desna@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456767',
            'role' => 'dokter',
            'branch_id' => 3,
            'status' => '0',
            'created_by' => 'budi',
            'created_at' => '2020-12-30'
        ]);        
    }
}