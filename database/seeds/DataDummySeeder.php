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
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);
        
        DB::table('branches')->insert([
            'branch_code' => 'KM',
            'branch_name' => 'Kembangan',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('branches')->insert([
            'branch_code' => 'TJ',
            'branch_name' => 'Tanjung Duren',
            'user_id' => '1',
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
            'created_by' => 'budi saputri',
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
            'created_by' => 'budi saputri',
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
            'created_by' => 'budi saputri',
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
            'created_by' => 'budi saputri',
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
            'created_by' => 'budi saputri',
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
            'created_by' => 'budi saputri',
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
            'created_by' => 'budi saputri',
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
            'created_by' => 'budi saputri',
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
            'created_by' => 'budi saputri',
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
            'created_by' => 'budi saputri',
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
            'created_by' => 'budi saputri',
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
            'created_by' => 'budi saputri',
            'created_at' => '2020-12-30'
        ]);
        
        //pasien
        DB::table('patients')->insert([
            'branch_id' => 1,
            'id_member' => 'BVC-P-AS-0001',
            'pet_category' => 'kucing',
            'pet_name' => 'kuki',
            'pet_gender' => 'betina',
            'pet_year_age' => 2,
            'pet_month_age' => 10,
            'owner_name' => 'agus',
            'owner_address' => 'tangerang selatan',
            'owner_phone_number' => '081234560987',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);
        
        DB::table('patients')->insert([
            'branch_id' => 3,
            'id_member' => 'BVC-P-KM-0001',
            'pet_category' => 'anjing',
            'pet_name' => 'rambo',
            'pet_gender' => 'jantan',
            'pet_year_age' => 2,
            'pet_month_age' => 10,
            'owner_name' => 'tina',
            'owner_address' => 'lebak bulus',
            'owner_phone_number' => '081234560988',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('patients')->insert([
            'branch_id' => 2,
            'id_member' => 'BVC-P-TJ-0001',
            'pet_category' => 'kucing',
            'pet_name' => 'tabi',
            'pet_gender' => 'tidak diketahui',
            'pet_year_age' => 2,
            'pet_month_age' => 10,
            'owner_name' => 'tono',
            'owner_address' => 'pondok indah',
            'owner_phone_number' => '081234560989',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        //category_item
        DB::table('category_item')->insert([
            'category_name' => 'Antibiotik',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('category_item')->insert([
            'category_name' => 'Suplemen',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('category_item')->insert([
            'category_name' => 'Anti Radang',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        //unit_item
        DB::table('unit_item')->insert([
            'unit_name' => 'Pcs',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('unit_item')->insert([
            'unit_name' => 'Box',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('unit_item')->insert([
            'unit_name' => 'Strip',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        //list_of_items
        DB::table('list_of_items')->insert([
            'item_name' => 'Vosea',
            'total_item' => '5',
            'unit_item_id' => '3',
            'category_item_id' => '2',
            'branch_id' => '1',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('list_of_items')->insert([
            'item_name' => 'Kaotin',
            'total_item' => '7',
            'unit_item_id' => '2',
            'category_item_id' => '2',
            'branch_id' => '2',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('list_of_items')->insert([
            'item_name' => 'Doxy',
            'total_item' => '9',
            'unit_item_id' => '3',
            'category_item_id' => '2',
            'branch_id' => '3',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('list_of_items')->insert([
            'item_name' => 'Dexa',
            'total_item' => '11',
            'unit_item_id' => '3',
            'category_item_id' => '2',
            'branch_id' => '3',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        //service_categories
        DB::table('service_categories')->insert([
            'category_name' => 'Operasi',
            'user_id' => '1',
            'created_at' => '2020-12-29'
        ]);

        DB::table('service_categories')->insert([
            'category_name' => 'Tindakan 1',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('service_categories')->insert([
            'category_name' => 'Tindakan 2',
            'user_id' => '1',
            'created_at' => '2020-12-31'
        ]);

        //list_of_services
        DB::table('list_of_services')->insert([
            'service_name' => 'rawat jalan',
            'service_category_id' => '2',
            'branch_id' => '1',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('list_of_services')->insert([
            'service_name' => 'rawat inap',
            'service_category_id' => '3',
            'branch_id' => '2',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('list_of_services')->insert([
            'service_name' => 'operasi caesar',
            'service_category_id' => '1',
            'branch_id' => '3',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('list_of_services')->insert([
            'service_name' => 'operasi biasa',
            'service_category_id' => '1',
            'branch_id' => '3',
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        //price_services
        DB::table('price_services')->insert([
            'list_of_services_id' => '1',
            'selling_price' => 100000,
            'capital_price' => 0,
            'doctor_fee' => 70000,
            'petshop_fee' => 30000,
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('price_services')->insert([
            'list_of_services_id' => '2',
            'selling_price' => 120000,
            'capital_price' => 20000,
            'doctor_fee' => 70000,
            'petshop_fee' => 30000,
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('price_services')->insert([
            'list_of_services_id' => '3',
            'selling_price' => 200000,
            'capital_price' => 60000,
            'doctor_fee' => 70000,
            'petshop_fee' => 70000,
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        //price_items
        DB::table('price_items')->insert([
            'list_of_items_id' => '1',
            'selling_price' => 200000,
            'capital_price' => 60000,
            'doctor_fee' => 70000,
            'petshop_fee' => 70000,
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('price_items')->insert([
            'list_of_items_id' => '2',
            'selling_price' => 200000,
            'capital_price' => 60000,
            'doctor_fee' => 70000,
            'petshop_fee' => 70000,
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('price_items')->insert([
            'list_of_items_id' => '3',
            'selling_price' => 200000,
            'capital_price' => 60000,
            'doctor_fee' => 70000,
            'petshop_fee' => 70000,
            'user_id' => '1',
            'created_at' => '2020-12-30'
        ]);

        //registrations
        DB::table('registrations')->insert([
            'id_number' => 'BVC-RP-AS-0001',
            'patient_id' => '2',
            'complaint' => 'pilek',
            'registrant' => 'agus',
            'user_id' => '1',
            'doctor_user_id' => '3',
            'acceptance_status' => '0',
            'created_at' => '2020-12-30'
        ]);

        DB::table('registrations')->insert([
            'id_number' => 'BVC-RP-AS-0002',
            'patient_id' => '3',
            'complaint' => 'gatal-gatal',
            'registrant' => 'kuncoro',
            'user_id' => '1',
            'doctor_user_id' => '4',
            'acceptance_status' => '1',
            'created_at' => '2020-12-30'
        ]);

        DB::table('registrations')->insert([
            'id_number' => 'BVC-RP-AS-0003',
            'patient_id' => '1',
            'complaint' => 'batuk',
            'registrant' => 'supri',
            'user_id' => '1',
            'doctor_user_id' => '7',
            'acceptance_status' => '2',
            'created_at' => '2020-12-30'
        ]);
    }
}
