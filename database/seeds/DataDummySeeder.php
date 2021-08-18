<?php

use Illuminate\Database\Seeder;

class DataDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branches')->insert([
            'branch_code' => 'AS',
            'branch_name' => 'Alam Sutera',
            'address' => 'Ruko Spectra blok 23c no 19 Alam Sutera',
            'user_id' => '1',
            'created_at' => '2020-12-30',
        ]);

        DB::table('branches')->insert([
            'branch_code' => 'KB',
            'branch_name' => 'Kebagusan',
            'address' => 'Jl. Kebagusan Raya no 48 Jaksel',
            'user_id' => '1',
            'created_at' => '2020-12-30',
        ]);

        DB::table('branches')->insert([
            'branch_code' => 'TJ',
            'branch_name' => 'Tanjung Duren',
            'address' => 'Jl. Tanjung Duren Barat 1 no 19c Jakbar',
            'user_id' => '1',
            'created_at' => '2020-12-30',
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
            'created_by' => 'budi saputri',
            'created_at' => '2020-12-30',
        ]);

        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'susi',
            'fullname' => 'susi saputri',
            'email' => 'susi@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456788',
            'role' => 'kasir',
            'branch_id' => 1,
            'status' => '1',
            'created_by' => 'budi saputri',
            'created_at' => '2020-12-30',
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
            'created_by' => 'budi saputri',
            'created_at' => '2020-12-30',
        ]);

        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'susan',
            'fullname' => 'susan saputri',
            'email' => 'susan@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456768',
            'role' => 'kasir',
            'branch_id' => 2,
            'status' => '1',
            'created_by' => 'budi saputri',
            'created_at' => '2020-12-30',
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
            'created_by' => 'budi saputri',
            'created_at' => '2020-12-30',
        ]);

        DB::table('users')->insert([
            'staffing_number' => '12345',
            'username' => 'sasa',
            'fullname' => 'sasa saputri',
            'email' => 'sasa@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081223456778',
            'role' => 'kasir',
            'branch_id' => 3,
            'status' => '1',
            'created_by' => 'budi saputri',
            'created_at' => '2020-12-30',
        ]);

        //list_of_items
        DB::table('list_of_items')->insert([
            'item_name' => 'Whiskas',
            'total_item' => 1,
            'selling_price' => '40000.00',
            'capital_price' => '30000.00',
            'profit' => '10000.00',
            'image' => '/documents/JdMOX2oUaLPh4fwsMKsblOZXMJLsY6KcuJUSZsMq.jpg',
            'category' => 'cat_food',
            'branch_id' => 1,
            'isDeleted' => 0,
            'user_id' => 1,
            'user_update_id' => null,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => '2021-08-18 04:59:36',
            'updated_at' => '2021-08-18 04:59:36',
        ]);

        DB::table('list_of_items')->insert([
            'item_name' => 'Maxi',
            'total_item' => 2,
            'selling_price' => '80000.00',
            'capital_price' => '30000.00',
            'profit' => '50000.00',
            'image' => '/documents/0dpkGU34Ab1qS7eVnWDCROVzxmRCZsbMIPdYf7fw.jpg',
            'category' => 'dog_food',
            'branch_id' => 1,
            'isDeleted' => 0,
            'user_id' => 1,
            'user_update_id' => null,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => '2021-08-18 08:35:05',
            'updated_at' => '2021-08-18 08:35:05',
        ]);

        DB::table('list_of_items')->insert([
            'item_name' => 'Purina',
            'total_item' => 9,
            'selling_price' => '70000.00',
            'capital_price' => '30000.00',
            'profit' => '40000.00',
            'image' => '/documents/XDsghNE6FNsCxUeDl1u4arlu2YT6zV6eRRP7VlAj.jpg',
            'category' => 'animal_food',
            'branch_id' => 1,
            'isDeleted' => 0,
            'user_id' => 1,
            'user_update_id' => null,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => '2021-08-18 08:38:22',
            'updated_at' => '2021-08-18 08:38:22',
        ]);

        DB::table('list_of_items')->insert([
            'item_name' => 'Prebiotik',
            'total_item' => 8,
            'selling_price' => '90000.00',
            'capital_price' => '20000.00',
            'profit' => '70000.00',
            'image' => '/documents/JvY7MvEIP4Z4Dje3Sz9G05uE4F6Yl7FJH7ScM8m3.jpg',
            'category' => 'vitamin',
            'branch_id' => 1,
            'isDeleted' => 0,
            'user_id' => 1,
            'user_update_id' => null,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => '2021-08-18 08:38:44',
            'updated_at' => '2021-08-18 08:38:44',
        ]);

        DB::table('list_of_items')->insert([
            'item_name' => 'Kandang Kecil',
            'total_item' => 3,
            'selling_price' => '100000.00',
            'capital_price' => '20000.00',
            'profit' => '80000.00',
            'image' => '/documents/xkAZgRG1MnWLG5a3H6Hxgy1Bs4J8Kceo9gQkE4Rg.jpg',
            'category' => 'cage',
            'branch_id' => 1,
            'isDeleted' => 0,
            'user_id' => 1,
            'user_update_id' => null,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => '2021-08-18 08:43:12',
            'updated_at' => '2021-08-18 08:43:12',
        ]);

        DB::table('list_of_items')->insert([
            'item_name' => 'Pita',
            'total_item' => 12,
            'selling_price' => '100000.00',
            'capital_price' => '20000.00',
            'profit' => '80000.00',
            'image' => '/documents/YGkxg2ylAs6Z57SyTb2UFuQIiC0Kdo8UqR6ms3Us.png',
            'category' => 'accessories',
            'branch_id' => 1,
            'isDeleted' => 0,
            'user_id' => 1,
            'user_update_id' => null,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => '2021-08-18 08:44:08',
            'updated_at' => '2021-08-18 08:44:08',
        ]);

        DB::table('list_of_items')->insert([
            'item_name' => 'Gunting Kuku',
            'total_item' => 3,
            'selling_price' => '10000.00',
            'capital_price' => '2000.00',
            'profit' => '8000.00',
            'image' => '/documents/50WFAGy2nVItrWBuXDbm340zKMNhj2F7Z5GhgsOZ.png',
            'category' => 'others',
            'branch_id' => 1,
            'isDeleted' => 0,
            'user_id' => 1,
            'user_update_id' => null,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => '2021-08-18 08:44:55',
            'updated_at' => '2021-08-18 08:44:55',
        ]);

    }
}
