<?php

namespace App\Http\Controllers;

use App\Models\CheckUpResult;
use App\Models\DetailItemPatient;
use App\Models\DetailServicePatient;
use App\Models\ListofPaymentItem;
use App\Models\ListofPayments;
use App\Models\ListofPaymentService;
use DB;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function DropDownPatient(Request $request)
    {
        if ($request->user()->role == 'dokter') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $data_check = DB::table('list_of_payments')
            ->select('list_of_payments.check_up_result_id')
            ->get();

        $res = "";
        $res2 = "";

        foreach ($data_check as $dat) {
            $res = $res . (string) $dat->check_up_result_id . ",";
        }

        $res = rtrim($res, ", ");

        $myArray = explode(',', $res);

        $data = DB::table('check_up_results')
            ->join('registrations', 'check_up_results.patient_registration_id', '=', 'registrations.id')
            ->join('users', 'registrations.user_id', '=', 'users.id')
            ->join('users as user_doctor', 'registrations.doctor_user_id', '=', 'user_doctor.id')
            ->join('branches', 'user_doctor.branch_id', '=', 'branches.id')
            ->join('patients', 'registrations.patient_id', '=', 'patients.id')
            ->select('check_up_results.id as check_up_result_id', 'registrations.id_number as registration_number', 'patients.pet_name');

        $data = $data->whereNotIn('check_up_results.id', $myArray);

        if ($request->user()->role == 'resepsionis') {
            $data = $data->where('user_doctor.branch_id', '=', $request->user()->branch_id);
        }

        $data = $data->get();

        return response()->json($data, 200);
    }

    public function index(Request $request)
    {
        if ($request->user()->role == 'dokter') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $data = DB::table('list_of_payments')
            ->join('check_up_results', 'list_of_payments.check_up_result_id', '=', 'check_up_results.id')
            ->join('users', 'check_up_results.user_id', '=', 'users.id')
            ->join('branches', 'users.branch_id', '=', 'branches.id')
            ->join('registrations', 'check_up_results.patient_registration_id', '=', 'registrations.id')
            ->join('patients', 'registrations.patient_id', '=', 'patients.id')
            ->select('list_of_payments.id as list_of_payment_id', 'check_up_results.id as check_up_result_id', 'registrations.id_number as registration_number',
                'patients.id_member as patient_number', 'patients.pet_category', 'patients.pet_name', 'registrations.complaint',
                'check_up_results.status_outpatient_inpatient', 'users.fullname as created_by',
                DB::raw("DATE_FORMAT(check_up_results.created_at, '%d %b %Y') as created_at"));

        if ($request->user()->role == 'resepsionis') {
            $data = $data->where('users.branch_id', '=', $request->user()->branch_id);
        }

        if ($request->orderby) {
            $data = $data->orderBy($request->column, $request->orderby);
        }

        $data = $data->orderBy('check_up_results.id', 'desc');

        $data = $data->get();

        return response()->json($data, 200);
    }

    public function detail(Request $request)
    {
        if ($request->user()->role == 'dokter') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $data = ListofPayments::find($request->list_of_payment_id);

        if (is_null($data)) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data Pembayaran Tidak Ditemukan!'],
            ], 404);
        }

        $data_check_up_result = CheckUpResult::find($data->check_up_result_id);

        $data->check_up_result = $data_check_up_result;

        $registration = DB::table('registrations')
            ->join('patients', 'registrations.patient_id', '=', 'patients.id')
            ->select('registrations.id_number as registration_number', 'patients.id as patient_id', 'patients.id_member as patient_number', 'patients.pet_category',
                'patients.pet_name', 'patients.pet_gender', 'patients.pet_year_age', 'patients.pet_month_age', 'patients.owner_name', 'patients.owner_address',
                'patients.owner_phone_number', 'registrations.complaint', 'registrations.registrant')
            ->where('registrations.id', '=', $data_check_up_result->patient_registration_id)
            ->first();

        $data->registration = $registration;

        $user = DB::table('check_up_results')
            ->join('users', 'check_up_results.user_id', '=', 'users.id')
            ->select('users.id as user_id', 'users.username as username')
            ->where('users.id', '=', $data->user_id)
            ->first();

        $data->user = $user;

        $services = DB::table('detail_service_patients')
            ->join('price_services', 'detail_service_patients.price_service_id', '=', 'price_services.id')
            ->join('list_of_services', 'price_services.list_of_services_id', '=', 'list_of_services.id')
            ->join('service_categories', 'list_of_services.service_category_id', '=', 'service_categories.id')
            ->join('users', 'detail_service_patients.user_id', '=', 'users.id')
            ->select('detail_service_patients.id as detail_service_patient_id', 'price_services.id as price_service_id',
                'list_of_services.id as list_of_service_id', 'list_of_services.service_name',
                'detail_service_patients.quantity', DB::raw("TRIM(detail_service_patients.price_overall)+0 as price_overall"),
                'detail_service_patients.status_paid_off', 'service_categories.category_name', DB::raw("TRIM(price_services.selling_price)+0 as selling_price"),
                'users.fullname as created_by', DB::raw("DATE_FORMAT(detail_service_patients.created_at, '%d %b %Y') as created_at"))
            ->where('detail_service_patients.check_up_result_id', '=', $data->id)
            ->orderBy('detail_service_patients.id', 'desc')
            ->get();

        $data['services'] = $services;

        $item = DB::table('detail_item_patients')
            ->join('price_items', 'detail_item_patients.price_item_id', '=', 'price_items.id')
            ->join('list_of_items', 'price_items.list_of_items_id', '=', 'list_of_items.id')
            ->join('category_item', 'list_of_items.category_item_id', '=', 'category_item.id')
            ->join('unit_item', 'list_of_items.unit_item_id', '=', 'unit_item.id')
            ->join('price_medicine_groups', 'detail_item_patients.medicine_group_id', '=', 'price_medicine_groups.id')
            ->join('medicine_groups', 'price_medicine_groups.medicine_group_id', '=', 'medicine_groups.id')
            ->join('users', 'detail_item_patients.user_id', '=', 'users.id')
            ->select('detail_item_patients.id as detail_item_patients_id', 'list_of_items.id as list_of_item_id', 'price_items.id as price_item_id', 'list_of_items.item_name', 'detail_item_patients.quantity',
                DB::raw("TRIM(detail_item_patients.price_overall)+0 as price_overall"), 'unit_item.unit_name',
                'category_item.category_name', DB::raw("TRIM(price_items.selling_price)+0 as selling_price"), 'detail_item_patients.medicine_group_id as medicine_group_id',
                'medicine_groups.group_name', 'detail_item_patients.status_paid_off', 'users.fullname as created_by', DB::raw("DATE_FORMAT(detail_item_patients.created_at, '%d %b %Y') as created_at"))
            ->where('detail_item_patients.check_up_result_id', '=', $data->id)
            ->orderBy('detail_item_patients.id', 'desc')
            ->get();

        $data['item'] = $item;

        $paid_services = DB::table('list_of_payment_services')
            ->join('detail_service_patients', 'list_of_payment_services.detail_service_patient_id', '=', 'detail_service_patients.id')
            ->join('price_services', 'detail_service_patients.price_service_id', '=', 'price_services.id')
            ->join('list_of_services', 'price_services.list_of_services_id', '=', 'list_of_services.id')
            ->join('service_categories', 'list_of_services.service_category_id', '=', 'service_categories.id')
            ->join('check_up_results', 'list_of_payment_services.check_up_result_id', '=', 'check_up_results.id')
            ->join('users', 'detail_service_patients.user_id', '=', 'users.id')
            ->select('list_of_payment_services.id as list_of_payment_service_id',
                DB::raw("DATE_FORMAT(list_of_payment_services.created_at, '%d %b %Y') as paid_date"),
                DB::raw("DATE_FORMAT(detail_service_patients.created_at, '%d %b %Y') as created_at"),
                'users.fullname as created_by', 'list_of_services.service_name',
                'detail_service_patients.quantity', DB::raw("TRIM(detail_service_patients.price_overall)+0 as price_overall"),
                'service_categories.category_name', DB::raw("TRIM(price_services.selling_price)+0 as selling_price"))
            ->where('detail_service_patients.check_up_result_id', '=', $data->id)
            ->where('detail_service_patients.status_paid_off', '=', 1)
            ->orderBy('list_of_payment_services.id', 'desc')
            ->get();

        $data['paid_services'] = $paid_services;

        $paid_item = DB::table('list_of_payment_items')
            ->join('detail_item_patients', 'list_of_payment_items.detail_item_patient_id', '=', 'detail_item_patients.id')
            ->join('price_items', 'detail_item_patients.price_item_id', '=', 'price_items.id')
            ->join('list_of_items', 'price_items.list_of_items_id', '=', 'list_of_items.id')
            ->join('category_item', 'list_of_items.category_item_id', '=', 'category_item.id')
            ->join('unit_item', 'list_of_items.unit_item_id', '=', 'unit_item.id')
            ->join('price_medicine_groups', 'detail_item_patients.medicine_group_id', '=', 'price_medicine_groups.id')
            ->join('medicine_groups', 'price_medicine_groups.medicine_group_id', '=', 'medicine_groups.id')
            ->join('users', 'detail_item_patients.user_id', '=', 'users.id')
            ->select('list_of_payment_items.id as list_of_payment_item_id', 'detail_item_patients.id as detail_item_patients_id',
                'list_of_items.id as list_of_item_id', 'price_items.id as price_item_id', 'list_of_items.item_name',
                'detail_item_patients.quantity', DB::raw("TRIM(detail_item_patients.price_overall)+0 as price_overall"), 'unit_item.unit_name',
                'category_item.category_name', DB::raw("TRIM(price_items.selling_price)+0 as selling_price"), 'detail_item_patients.medicine_group_id as medicine_group_id',
                'medicine_groups.group_name', 'users.fullname as created_by', DB::raw("DATE_FORMAT(detail_item_patients.created_at, '%d %b %Y') as created_at"),
                DB::raw("DATE_FORMAT(list_of_payment_items.created_at, '%d %b %Y') as paid_date"))
            ->where('detail_item_patients.check_up_result_id', '=', $data->id)
            ->where('detail_item_patients.status_paid_off', '=', 1)
            ->orderBy('list_of_payment_items.id', 'desc')
            ->get();

        $data['paid_item'] = $paid_item;

        return response()->json($data, 200);
    }

    public function create(Request $request)
    {
        if ($request->user()->role == 'dokter') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        //validasi
        $check_list_of_payment = DB::table('list_of_payments')
            ->where('check_up_result_id', '=', $request->check_up_result_id)
            ->count();

        if ($check_list_of_payment != 0) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['Data Pembayaran ini sudah pernah ada!'],
            ], 422);
        }

        $check_up_result = DB::table('check_up_results')
            ->select('status_paid_off')
            ->where('id', '=', $request->check_up_result_id)
            ->first();

        if ($check_up_result->status_paid_off == 1) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['Data Pemeriksaan ini sudah pernah dilunaskan!'],
            ], 422);
        }

        $services = $request->service_payment;
        $result_services = json_decode($services, true);

        if (count($result_services) == 0) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['Data Jasa Harus dipilih minimal 1!'],
            ], 422);
        }

        foreach ($result_services as $key_service) {

            $check_service = DetailServicePatient::find($key_service['detail_service_patient_id']);

            if (is_null($check_service)) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Data Hasil Pemeriksaan Layanan Pasien tidak ditemukan!'],
                ], 404);
            }

            $check_service_name = DB::table('detail_service_patients')
                ->join('price_services', 'detail_service_patients.price_service_id', '=', 'price_services.id')
                ->join('list_of_services', 'price_services.list_of_services_id', '=', 'list_of_services.id')
                ->select('list_of_services.service_name as service_name')
                ->where('detail_service_patients.id', '=', $key_service['detail_service_patient_id'])
                ->first();

            if (is_null($check_service_name)) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Data List of Services not found!'],
                ], 404);
            }

            $check_detail_service = DB::table('detail_service_patients')
                ->select('id')
                ->where('status_paid_off', '=', 1)
                ->where('id', '=', $key_service['detail_service_patient_id'])
                ->first();

            if ($check_detail_service) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Jasa ' . $check_service_name->service_name . ' sudah pernah dibayar sebelumnya!'],
                ], 404);
            }

        }

        $items = $request->item_payment;
        $result_item = json_decode($items, true);

        if (count($result_item) == 0) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['Data Barang Harus dipilih minimal 1!'],
            ], 422);
        }

        foreach ($result_item as $value_item) {

            $check_item = DetailItemPatient::find($value_item['detail_item_patient_id']);

            if (is_null($check_item)) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Data barang pasien tidak ditemukan!'],
                ], 404);
            }

            $check_item_name = DB::table('detail_item_patients')
                ->join('price_items', 'detail_item_patients.price_item_id', '=', 'price_items.id')
                ->join('list_of_items', 'price_items.list_of_items_id', '=', 'list_of_items.id')
                ->select('list_of_items.item_name as item_name')
                ->where('detail_item_patients.id', '=', $value_item['detail_item_patient_id'])
                ->first();

            if (is_null($check_item_name)) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Data List of Item not found!'],
                ], 404);
            }

            $check_detail_item = DB::table('detail_item_patients')
                ->select('id')
                ->where('status_paid_off', '=', 1)
                ->where('id', '=', $value_item['detail_item_patient_id'])
                ->first();

            if ($check_detail_item) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Data Barang ' . $check_item_name->item_name . ' sudah pernah dibayar sebelumnya!'],
                ], 404);
            }
        }

        //simpan data jasa
        foreach ($result_services as $key_service) {

            $item = ListofPaymentService::create([
                'detail_service_patient_id' => $key_service['detail_service_patient_id'],
                'check_up_result_id' => $request->check_up_result_id,
                'user_id' => $request->user()->id,
            ]);

            $check_service = DetailServicePatient::find($key_service['detail_service_patient_id']);

            $check_service->status_paid_off = 1;
            $check_service->user_update_id = $request->user()->id;
            $check_service->updated_at = \Carbon\Carbon::now();
            $check_service->save();
        }

        //simpan data barang
        foreach ($result_item as $value_item) {

            $item = ListofPaymentItem::create([
                'detail_item_patient_id' => $value_item['detail_item_patient_id'],
                'check_up_result_id' => $request->check_up_result_id,
                'user_id' => $request->user()->id,
            ]);

            $check_item = DetailItemPatient::find($value_item['detail_item_patient_id']);

            $check_item->status_paid_off = 1;
            $check_item->user_update_id = $request->user()->id;
            $check_item->updated_at = \Carbon\Carbon::now();
            $check_item->save();
        }

        //cek kelunasan jasa

        $count_payed_service = DB::table('list_of_payment_services')
            ->where('check_up_result_id', '=', $request->check_up_result_id)
            ->count();

        $count_service = DB::table('detail_service_patients')
            ->select('id')
            ->where('check_up_result_id', '=', $request->check_up_result_id)
            ->count();

        //cek kelunasan barang

        $count_payed_item = DB::table('list_of_payment_items')
            ->where('check_up_result_id', '=', $request->check_up_result_id)
            ->count();

        $count_item = DB::table('detail_item_patients')
            ->select('id')
            ->where('check_up_result_id', '=', $request->check_up_result_id)
            ->count();

        if ($count_payed_service == $count_service && $count_payed_item == $count_item) {

            $check_up_result = CheckUpResult::find($request->check_up_result_id);

            $check_up_result->status_paid_off = 1;
            $check_up_result->user_update_id = $request->user()->id;
            $check_up_result->updated_at = \Carbon\Carbon::now();
            $check_up_result->save();
        }

        $list_of_payment = ListofPayments::create([
            'check_up_result_id' => $request->check_up_result_id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(
            [
                'message' => 'Tambah Data Berhasil!',
            ], 200
        );
    }

    public function update(Request $request)
    {
        if ($request->user()->role == 'dokter') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        //validasi
        $check_list_of_payment = DB::table('list_of_payments')
            ->where('check_up_result_id', '=', $request->check_up_result_id)
            ->count();

        if ($check_list_of_payment == 0) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['Data Pembayaran ini belum pernah ada!'],
            ], 422);
        }

        $check_up_result = DB::table('check_up_results')
            ->select('status_paid_off')
            ->where('id', '=', $request->check_up_result_id)
            ->first();

        if ($check_up_result->status_paid_off == 1) {

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['Data Pemeriksaan ini sudah pernah dilunaskan!'],
            ], 422);
        }

        $services = $request->service_payment;
        $result_services = json_decode(json_encode($services), true);

        if (count($result_services) == 0) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['Data Jasa Harus dipilih minimal 1!'],
            ], 422);
        }

        foreach ($result_services as $key_service) {

            if ($key_service['detail_service_patient_id']) {

                $check_service = DetailServicePatient::find($key_service['detail_service_patient_id']);

                if (is_null($check_service)) {
                    return response()->json([
                        'message' => 'The data was invalid.',
                        'errors' => ['Data tidak ditemukan!'],
                    ], 404);
                }

                $check_service_name = DB::table('detail_service_patients')
                    ->join('price_services', 'detail_service_patients.price_service_id', '=', 'price_services.id')
                    ->join('list_of_services', 'price_services.list_of_services_id', '=', 'list_of_services.id')
                    ->select('list_of_services.service_name as service_name')
                    ->where('detail_service_patients.id', '=', $key_service['detail_service_patient_id'])
                    ->first();

                if (is_null($check_service_name)) {
                    return response()->json([
                        'message' => 'The data was invalid.',
                        'errors' => ['Data List of Services not found!'],
                    ], 404);
                }

                $check_detail_service = DB::table('detail_service_patients')
                    ->select('id')
                    ->where('status_paid_off', '=', 1)
                    ->where('id', '=', $key_service['detail_service_patient_id'])
                    ->first();

                if ($check_detail_service) {
                    return response()->json([
                        'message' => 'The data was invalid.',
                        'errors' => ['Jasa ' . $check_service_name->service_name . ' sudah pernah dibayar sebelumnya!'],
                    ], 404);
                }

            }
        }

        $items = $request->item_payment;
        $result_item = json_decode(json_encode($items), true);

        if (count($result_item) == 0) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['Data Barang Harus dipilih minimal 1!'],
            ], 422);
        }

        foreach ($result_item as $value_item) {

            if ($value_item['detail_item_patient_id']) {

                $check_item = DetailItemPatient::find($value_item['detail_item_patient_id']);

                if (is_null($check_item)) {
                    return response()->json([
                        'message' => 'The data was invalid.',
                        'errors' => ['Data tidak ditemukan!'],
                    ], 404);
                }

                $check_item_name = DB::table('detail_item_patients')
                    ->join('price_items', 'detail_item_patients.price_item_id', '=', 'price_items.id')
                    ->join('list_of_items', 'price_items.list_of_items_id', '=', 'list_of_items.id')
                    ->select('list_of_items.item_name as item_name')
                    ->where('detail_item_patients.id', '=', $value_item['detail_item_patient_id'])
                    ->first();

                if (is_null($check_item_name)) {
                    return response()->json([
                        'message' => 'The data was invalid.',
                        'errors' => ['Data List of Item not found!'],
                    ], 404);
                }

                $check_detail_item = DB::table('detail_item_patients')
                    ->select('id')
                    ->where('status_paid_off', '=', 1)
                    ->where('id', '=', $value_item['detail_item_patient_id'])
                    ->first();

                if ($check_detail_item) {
                    return response()->json([
                        'message' => 'The data was invalid.',
                        'errors' => ['Data Barang ' . $check_item_name->item_name . ' sudah pernah dibayar sebelumnya!'],
                    ], 404);
                }

            }
        }

        //simpan data jasa
        foreach ($result_services as $key_service) {

            if ($key_service['detail_service_patient_id']) {

                $item = ListofPaymentService::create([
                    'detail_service_patient_id' => $key_service['detail_service_patient_id'],
                    'check_up_result_id' => $request->check_up_result_id,
                    'user_id' => $request->user()->id,
                ]);

                $check_service = DetailServicePatient::find($key_service['detail_service_patient_id']);

                $check_service->status_paid_off = 1;
                $check_service->user_update_id = $request->user()->id;
                $check_service->updated_at = \Carbon\Carbon::now();
                $check_service->save();
            }
        }

        //simpan data barang
        foreach ($result_item as $value_item) {

            if ($value_item['detail_item_patient_id']) {

                $item = ListofPaymentItem::create([
                    'detail_item_patient_id' => $value_item['detail_item_patient_id'],
                    'check_up_result_id' => $request->check_up_result_id,
                    'user_id' => $request->user()->id,
                ]);

                $check_item = DetailItemPatient::find($value_item['detail_item_patient_id']);

                $check_item->status_paid_off = 1;
                $check_item->user_update_id = $request->user()->id;
                $check_item->updated_at = \Carbon\Carbon::now();
                $check_item->save();

            }
        }

        //cek kelunasan jasa

        $count_payed_service = DB::table('list_of_payment_services')
            ->where('check_up_result_id', '=', $request->check_up_result_id)
            ->count();

        $count_service = DB::table('detail_service_patients')
            ->select('id')
            ->where('status_paid_off', '=', 1)
            ->where('check_up_result_id', '=', $request->check_up_result_id)
            ->count();

        //cek kelunasan barang

        $count_payed_item = DB::table('list_of_payment_items')
            ->where('check_up_result_id', '=', $request->check_up_result_id)
            ->count();

        $count_item = DB::table('detail_item_patients')
            ->select('id')
            ->where('status_paid_off', '=', 1)
            ->where('check_up_result_id', '=', $request->check_up_result_id)
            ->count();

        if ($count_payed_service == $count_service && $count_payed_item == $count_item) {

            $check_up_result = CheckUpResult::find($request->check_up_result_id);

            $check_up_result->status_paid_off = 1;
            $check_up_result->user_update_id = $request->user()->id;
            $check_up_result->updated_at = \Carbon\Carbon::now();
            $check_up_result->save();
        }

        return response()->json(
            [
                'message' => 'Tambah Data Berhasil!',
            ], 200
        );
    }

    // public function delete(Request $request)
    // {
    //     if ($request->user()->role == 'dokter') {
    //         return response()->json([
    //             'message' => 'The user role was invalid.',
    //             'errors' => ['Akses User tidak diizinkan!'],
    //         ], 403);
    //     }

    // }
}
