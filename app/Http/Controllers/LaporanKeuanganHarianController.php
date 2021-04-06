<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class LaporanKeuanganHarianController extends Controller
{
    public function index(Request $request)
    {
        //percobaan
        if ($request->user()->role == 'resepsionis' && $request->user()->role == 'dokter') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $data = DB::table('list_of_payments')
            ->join('check_up_results', 'list_of_payments.check_up_result_id', '=', 'check_up_results.id')

            ->join('list_of_payment_items', 'check_up_results.id', '=', 'list_of_payment_items.check_up_result_id')
            ->join('detail_item_patients', 'list_of_payment_items.detail_item_patient_id', '=', 'detail_item_patients.id')
            ->join('price_items', 'detail_item_patients.price_item_id', '=', 'price_items.id')

            ->join('list_of_payment_services', 'check_up_results.id', '=', 'list_of_payment_services.check_up_result_id')
            ->join('detail_service_patients', 'list_of_payment_services.detail_service_patient_id', '=', 'detail_service_patients.id')
            ->join('price_services', 'detail_service_patients.price_service_id', '=', 'price_services.id')

            ->join('users', 'check_up_results.user_id', '=', 'users.id')
            ->join('branches', 'users.branch_id', '=', 'branches.id')
            ->join('registrations', 'check_up_results.patient_registration_id', '=', 'registrations.id')
            ->join('patients', 'registrations.patient_id', '=', 'patients.id')
            ->select('list_of_payments.id as list_of_payment_id', 'check_up_results.id as check_up_result_id', 'registrations.id_number as registration_number',
                'patients.id_member as patient_number', 'patients.pet_category', 'patients.pet_name', 'registrations.complaint',

                DB::raw("TRIM(SUM(detail_item_patients.price_overall) + SUM(detail_service_patients.price_overall))+0 as price_overall"),
                DB::raw("TRIM(SUM(price_items.capital_price) + SUM(price_services.capital_price))+0 as capital_price"),
                DB::raw("TRIM(SUM(price_items.doctor_fee) + SUM(price_services.doctor_fee))+0 as doctor_fee"),
                DB::raw("TRIM(SUM(price_items.petshop_fee) + SUM(price_services.petshop_fee))+0 as petshop_fee"),

                'users.fullname as created_by', DB::raw("DATE_FORMAT(check_up_results.created_at, '%d %b %Y') as created_at"))
            ->groupBy('list_of_payments.id', 'check_up_results.id', 'registrations.id_number', 'patients.id_member', 'patients.pet_category', 'patients.pet_name',
                'registrations.complaint', 'users.fullname', 'check_up_results.created_at')
            ->get();

        return response()->json($data, 200);
    }

    public function detail(Request $request)
    {
        if ($request->user()->role == 'resepsionis' && $request->user()->role == 'dokter') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        
    }
}
