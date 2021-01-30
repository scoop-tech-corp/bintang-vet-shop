<?php

namespace App\Http\Controllers;

use App\Models\CheckUpResult;
use App\Models\DetailItemOutPatient;
use App\Models\DetailItemInPatient;
use App\Models\DetailServiceOutPatient;
use App\Models\DetailServiceInPatient;
use App\Models\HistoryItemMovement;
use App\Models\ListofItems;
use App\Models\ListofServices;
use App\Models\Registration;
use DB;
use Illuminate\Http\Request;
use Validator;

class HasilPemeriksaanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $data = DB::table('check_up_results')
            ->join('users', 'check_up_results.user_id', '=', 'users.id')
            ->join('registrations', 'check_up_results.patient_registration_id', '=', 'registrations.id')
            ->join('patients', 'registrations.patient_id', '=', 'patients.id')
            ->select('check_up_results.id', 'registrations.id_number', 'patients.pet_category', 'patients.pet_name',
                'registrations.complaint', 'check_up_results.status_finish', 'check_up_results.status_outpatient_inpatient', 'users.fullname as created_by',
                DB::raw("DATE_FORMAT(check_up_results.created_at, '%d %b %Y') as created_at"));

        if ($request->user()->role == 'dokter') {
            $data = $data->where('users.id', '=', $request->user()->id);
        }

        if ($request->branch_id && $request->user()->role == 'admin') {
            $data = $data->where('users.branch_id', '=', $request->branch_id);
        }

        if ($request->keyword) {
            $data = $data->orwhere('registrations.id_number', 'like', '%' . $request->keyword . '%')
                ->orwhere('patients.pet_category', 'like', '%' . $request->keyword . '%')
                ->orwhere('patients.pet_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('registrations.complaint', 'like', '%' . $request->keyword . '%')
                ->orwhere('created_by', 'like', '%' . $request->keyword . '%');
        }

        if ($request->orderby) {
            $data = $data->orderBy($request->column, $request->orderby);
        }

        $data = $data->get();

        return response()->json($data, 200);
    }

    public function test(Request $request)
    {
        return $request->service;
    }

    public function create(Request $request)
    {
        if ($request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $validate = Validator::make($request->all(), [
            'patient_registration_id' => 'required|numeric|unique:check_up_results,patient_registration_id',
            'anamnesa' => 'required|string|min:10',
            'sign' => 'required|string|min:10',
            'diagnosa' => 'required|string|min:10',
            'status_finish' => 'required|numeric|min:1',
            'status_outpatient_inpatient' => 'required|numeric|min:1',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        //validasi item rawat jalan
        if (!(is_null($request->item))) {

            $temp_item = $request->item;

            $result_item = json_decode(json_encode($temp_item), true);

            foreach ($result_item as $value_item) {

                $check_storage = DB::table('list_of_items')
                    ->select('total_item')
                    ->where('id', '=', $value_item['item_id'])
                    ->first();

                if (is_null($check_storage)) {
                    return response()->json([
                        'message' => 'The data was invalid.',
                        'errors' => ['Data Total Item not found!'],
                    ], 404);
                }

                $check_storage_name = DB::table('list_of_items')
                    ->select('item_name')
                    ->where('id', '=', $value_item['item_id'])
                    ->first();

                if (is_null($check_storage_name)) {
                    return response()->json([
                        'message' => 'The data was invalid.',
                        'errors' => ['Data Total Item not found!'],
                    ], 404);
                }

                if ($value_item['quantity'] > $check_storage->total_item) {
                    return response()->json([
                        'message' => 'The given data was invalid.',
                        'errors' => ['Jumlah stok ' . $check_storage_name->item_name . ' pada rawat jalan kurang atau habis!'],
                    ], 422);
                }

                $list_of_items = ListofItems::find($value_item['item_id']);

                if (is_null($list_of_items)) {
                    return response()->json([
                        'message' => 'The data was invalid.',
                        'errors' => ['Data not found!'],
                    ], 404);
                }
            }
        }

        //validasi jasa
        if (is_null($request->service)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data Jasa Rawat Jalan Harus dipilih minimal 1!'],
            ], 404);
        }

        $services = $request->service;
        foreach ($services as $key_service) {

            $check_service = ListofServices::find($key_service);

            if (is_null($check_service)) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Data not found!'],
                ], 404);
            }
        }

        //cek rawat inap atau tidak menggunakan rawat inap
        if ($request->status_outpatient_inpatient == true) {
            //validasi item
            if (!(is_null($request->item_inpatient))) {

                $temp_item = $request->item_inpatient;

                $result_item_inpatient = json_decode(json_encode($temp_item), true);

                foreach ($result_item_inpatient as $value_item) {

                    $check_storage = DB::table('list_of_items')
                        ->select('total_item')
                        ->where('id', '=', $value_item['item_id'])
                        ->first();

                    if (is_null($check_storage)) {
                        return response()->json([
                            'message' => 'The data was invalid.',
                            'errors' => ['Data Total Item not found!'],
                        ], 404);
                    }

                    $check_storage_name = DB::table('list_of_items')
                        ->select('item_name')
                        ->where('id', '=', $value_item['item_id'])
                        ->first();

                    if (is_null($check_storage_name)) {
                        return response()->json([
                            'message' => 'The data was invalid.',
                            'errors' => ['Data Total Item not found!'],
                        ], 404);
                    }

                    if ($value_item['quantity'] > $check_storage->total_item) {
                        return response()->json([
                            'message' => 'The given data was invalid.',
                            'errors' => ['Jumlah stok ' . $check_storage_name->item_name . ' pada rawat inap kurang atau habis!'],
                        ], 422);
                    }

                    $list_of_items = ListofItems::find($value_item['item_id']);

                    if (is_null($list_of_items)) {
                        return response()->json([
                            'message' => 'The data was invalid.',
                            'errors' => ['Data not found!'],
                        ], 404);
                    }
                }
            }

            //validasi jasa
            if (is_null($request->service_inpatient)) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Data Jasa Rawat Inap Harus dipilih minimal 1!'],
                ], 404);
            }

            $services_inpatient = $request->service_inpatient;
            foreach ($services_inpatient as $key_service) {

                $check_service = ListofServices::find($key_service);

                if (is_null($check_service)) {
                    return response()->json([
                        'message' => 'The data was invalid.',
                        'errors' => ['Data not found!'],
                    ], 404);
                }
            }
        }

        //insert data
        $item = CheckUpResult::create([
            'patient_registration_id' => $request->patient_registration_id,
            'anamnesa' => $request->anamnesa,
            'sign' => $request->sign,
            'diagnosa' => $request->diagnosa,
            'status_finish' => $request->status_finish,
            'status_outpatient_inpatient' => $request->status_outpatient_inpatient,
            'user_id' => $request->user()->id,
        ]);

        if ($request->status_finish == true) {

            $registration = Registration::find($request->patient_registration_id);
            $registration->user_update_id = $request->user()->id;
            $registration->acceptance_status = 3;
            $registration->updated_at = \Carbon\Carbon::now();
            $registration->save();
        }

        foreach ($services as $key_service) {

            $service_list = DetailServiceOutPatient::create([
                'check_up_result_id' => $item->id,
                'service_id' => $key_service,
                'user_id' => $request->user()->id,
            ]);
        }

        if (!(is_null($request->item))) {

            foreach ($result_item as $value_item) {

                $item_list = DetailItemOutPatient::create([
                    'check_up_result_id' => $item->id,
                    'item_id' => $value_item['item_id'],
                    'quantity' => $value_item['quantity'],
                    'price_overall' => $value_item['price_overall'],
                    'user_id' => $request->user()->id,
                ]);

                $list_of_items = ListofItems::find($value_item['item_id']);

                $count_item = $list_of_items->total_item - $value_item['quantity'];

                $list_of_items->total_item = $count_item;
                $list_of_items->user_update_id = $request->user()->id;
                $list_of_items->updated_at = \Carbon\Carbon::now();
                $list_of_items->save();

                $item_history = HistoryItemMovement::create([
                    'item_id' => $value_item['item_id'],
                    'quantity' => $value_item['quantity'],
                    'status' => 'kurang',
                    'user_id' => $request->user()->id,
                ]);
            }
        }

        //simpan data rawat inap
        if ($request->status_outpatient_inpatient == true) {

            foreach ($services_inpatient as $key_service_inpatient) {

                $service_list = DetailServiceInPatient::create([
                    'check_up_result_id' => $item->id,
                    'service_id' => $key_service_inpatient,
                    'user_id' => $request->user()->id,
                ]);
            }
    
            if (!(is_null($request->item_inpatient))) {
    
                foreach ($result_item_inpatient as $value_item_inpatient) {
    
                    $item_list = DetailItemInPatient::create([
                        'check_up_result_id' => $item->id,
                        'item_id' => $value_item_inpatient['item_id'],
                        'quantity' => $value_item_inpatient['quantity'],
                        'price_overall' => $value_item_inpatient['price_overall'],
                        'user_id' => $request->user()->id,
                    ]);
    
                    $list_of_items = ListofItems::find($value_item_inpatient['item_id']);
    
                    $count_item = $list_of_items->total_item - $value_item_inpatient['quantity'];
    
                    $list_of_items->total_item = $count_item;
                    $list_of_items->user_update_id = $request->user()->id;
                    $list_of_items->updated_at = \Carbon\Carbon::now();
                    $list_of_items->save();
    
                    $item_history = HistoryItemMovement::create([
                        'item_id' => $value_item_inpatient['item_id'],
                        'quantity' => $value_item_inpatient['quantity'],
                        'status' => 'kurang',
                        'user_id' => $request->user()->id,
                    ]);
                }
            }
        }

        return response()->json(
            [
                'message' => 'Tambah Data Berhasil!',
            ], 200
        );
    }

    public function detail(Request $request)
    {
        if ($request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $data = CheckUpResult::with('service','service_inpatient', 'item','item_inpatient', 'registration', 'user')
            ->where('id', '=', $request->id)
            ->get();

        return response()->json($data, 200);
    }

    public function update(Request $request)
    {
        if ($request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        //validasi data hasil pemeriksaaan
        $validate = Validator::make($request->all(), [
            'patient_registration_id' => 'required|numeric',
            'anamnesa' => 'required|string|min:10',
            'sign' => 'required|string|min:10',
            'diagnosa' => 'required|string|min:10',
            'status_outpatient_inpatient' => 'required|numeric|min:1',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        //validasi data barang

        if (!(is_null($request->item))) {

            $temp_item = $request->item;

            $result_item = json_decode(json_encode($temp_item), true);

            foreach ($result_item as $value_item) {

                //cek untuk melakukan update atau create
                $detail_item = DetailItemOutPatient::find($value_item['id']);

                if (is_null($detail_item)) {
                    //kalau data baru

                    $list_of_items = ListofItems::find($value_item['item_id']);

                    if (is_null($list_of_items)) {
                        return response()->json([
                            'message' => 'The data was invalid.',
                            'errors' => ['Data List of Item not found!'],
                        ], 404);
                    }

                    $check_storage = DB::table('list_of_items')
                        ->select('total_item')
                        ->where('id', '=', $value_item['item_id'])
                        ->first();

                    if (is_null($check_storage)) {
                        return response()->json([
                            'message' => 'The data was invalid.',
                            'errors' => ['Data Total Item not found!'],
                        ], 404);
                    }

                    $check_storage_name = DB::table('list_of_items')
                        ->select('item_name')
                        ->where('id', '=', $value_item['item_id'])
                        ->first();

                    if (is_null($check_storage_name)) {
                        return response()->json([
                            'message' => 'The data was invalid.',
                            'errors' => ['Data Total Item not found!'],
                        ], 404);
                    }

                    if ($value_item['quantity'] > $check_storage->total_item) {
                        return response()->json([
                            'message' => 'The given data was invalid.',
                            'errors' => ['Jumlah stok ' . $check_storage_name->item_name . ' kurang atau habis!'],
                        ], 422);
                    }

                } else {
                    //kalau data yang sudah pernah ada

                    //untuk mendapatkan data stok terupdate
                    $check_stock = DB::table('list_of_items')
                        ->select('total_item')
                        ->where('id', '=', $value_item['item_id'])
                        ->first();

                    if (is_null($check_stock)) {
                        return response()->json([
                            'message' => 'The data was invalid.',
                            'errors' => ['Data List of Item not found!'],
                        ], 404);
                    }

                    $check_storage_name = DB::table('list_of_items')
                        ->select('item_name')
                        ->where('id', '=', $value_item['item_id'])
                        ->first();

                    if (is_null($check_storage_name)) {
                        return response()->json([
                            'message' => 'The data was invalid.',
                            'errors' => ['Data Total Item not found!'],
                        ], 404);
                    }

                    //untuk cek quantity yang sudah ada untuk mencari selisih penambahan
                    $check_item_result = DB::table('detail_item_out_patients')
                        ->select('quantity')
                        ->where('check_up_result_id', '=', $request->id)
                        ->where('item_id', '=', $value_item['item_id'])
                        ->first();

                    if (is_null($check_item_result)) {
                        return response()->json([
                            'message' => 'The data was invalid.',
                            'errors' => ['Data Item Check Up Result not found!'],
                        ], 404);
                    }

                    //validasi kalau data input lebih dari data awal
                    if ($value_item['quantity'] > $check_item_result->quantity) {

                        $res_value_item = $value_item['quantity'] - $check_item_result->quantity;

                        if ($res_value_item > $check_stock->total_item) {
                            return response()->json([
                                'message' => 'The given data was invalid.',
                                'errors' => ['Jumlah stok ' . $check_storage_name->item_name . ' kurang atau habis!'],
                            ], 422);
                        }

                        $list_of_items = ListofItems::find($value_item['item_id']);

                        if (is_null($list_of_items)) {
                            return response()->json([
                                'message' => 'The data was invalid.',
                                'errors' => ['Data List of Item not found!'],
                            ], 404);
                        }

                        $detail_item_out_patient = DetailItemOutPatient::find($value_item['id']);

                        if (is_null($detail_item_out_patient)) {

                            return response()->json([
                                'message' => 'The data was invalid.',
                                'errors' => ['Data not found!'],
                            ], 404);
                        }

                    } elseif ($value_item['quantity'] < $check_item_result->quantity) {

                        $res_value_item = $check_item_result->quantity - $value_item['quantity'];

                        $list_of_items = ListofItems::find($value_item['item_id']);

                        if (is_null($list_of_items)) {
                            return response()->json([
                                'message' => 'The data was invalid.',
                                'errors' => ['Data not found!'],
                            ], 404);
                        }

                        $detail_item_out_patient = DetailItemOutPatient::find($value_item['id']);

                        if (is_null($detail_item_out_patient)) {

                            return response()->json([
                                'message' => 'The data was invalid.',
                                'errors' => ['Data not found!'],
                            ], 404);
                        }
                    } else {

                        $list_of_items = ListofItems::find($value_item['item_id']);

                        if (is_null($list_of_items)) {
                            return response()->json([
                                'message' => 'The data was invalid.',
                                'errors' => ['Data not found!'],
                            ], 404);
                        }

                        $detail_item_out_patient = DetailItemOutPatient::find($value_item['id']);

                        if (is_null($detail_item_out_patient)) {

                            return response()->json([
                                'message' => 'The data was invalid.',
                                'errors' => ['Data not found!'],
                            ], 404);
                        }
                    }

                }
            }
        }

        //validasi data jasa
        if (is_null($request->service)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data Jasa Harus dipilih minimal 1!'],
            ], 404);
        }

        $temp_services = $request->service;

        $services = json_decode(json_encode($temp_services), true);

        foreach ($services as $key_service) {

            $detail_service_out_patient = DetailServiceOutPatient::find($key_service['id']);

            if (is_null($detail_service_out_patient)) {

                $check_service = ListofServices::find($key_service['service_id']);

                if (is_null($check_service)) {
                    return response()->json([
                        'message' => 'The data was invalid.',
                        'errors' => ['Data not found!'],
                    ], 404);
                }
            }
        }

        //update hasil pemeriksaan

        $check_up_result = CheckUpResult::find($request->id);

        if (is_null($check_up_result)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $check_up_result->patient_registration_id = $request->patient_registration_id;
        $check_up_result->anamnesa = $request->anamnesa;
        $check_up_result->sign = $request->sign;
        $check_up_result->diagnosa = $request->diagnosa;
        $check_up_result->status_outpatient_inpatient = $request->status_outpatient_inpatient;
        $check_up_result->user_update_id = $request->user()->id;
        $check_up_result->updated_at = \Carbon\Carbon::now();
        $check_up_result->save();

        if ($request->status_finish == true) {

            $registration = Registration::find($request->patient_registration_id);
            $registration->user_update_id = $request->user()->id;
            $registration->acceptance_status = 3;
            $registration->updated_at = \Carbon\Carbon::now();
            $registration->save();
        }

        //update jasa

        foreach ($services as $key_service) {

            $detail_service_out_patient = DetailServiceOutPatient::find($key_service['id']);

            if (is_null($detail_service_out_patient)) {

                $service_list = DetailServiceOutPatient::create([
                    'check_up_result_id' => $check_up_result->id,
                    'service_id' => $key_service['service_id'],
                    'user_id' => $request->user()->id,
                ]);

            } elseif ($key_service['status'] == 'del') {

                $detail_service_out_patient->delete();

            } else {

                $detail_service_out_patient->check_up_result_id = $check_up_result->id;
                $detail_service_out_patient->service_id = $key_service['service_id'];
                $detail_service_out_patient->user_update_id = $request->user()->id;
                $detail_service_out_patient->updated_at = \Carbon\Carbon::now();
                $detail_service_out_patient->save();

            }
        }

        //update barang
        if (!(is_null($request->item))) {

            foreach ($result_item as $value_item) {

                $detail_item = DetailItemOutPatient::find($value_item['id']);

                if (is_null($detail_item)) {

                    $item_list = DetailItemOutPatient::create([
                        'check_up_result_id' => $check_up_result->id,
                        'item_id' => $value_item['item_id'],
                        'quantity' => $value_item['quantity'],
                        'price_overall' => $value_item['price_overall'],
                        'user_id' => $request->user()->id,
                    ]);

                    $list_of_items = ListofItems::find($value_item['item_id']);

                    $count_item = $list_of_items->total_item - $value_item['quantity'];

                    $list_of_items->total_item = $count_item;
                    $list_of_items->user_update_id = $request->user()->id;
                    $list_of_items->updated_at = \Carbon\Carbon::now();
                    $list_of_items->save();

                    $item_history = HistoryItemMovement::create([
                        'item_id' => $value_item['item_id'],
                        'quantity' => $value_item['quantity'],
                        'status' => 'kurang',
                        'user_id' => $request->user()->id,
                    ]);

                } elseif ($value_item['status'] == 'del' || $value_item['quantity'] == 0) {

                    $check_item_result = DB::table('detail_item_out_patients')
                        ->select('quantity')
                        ->where('check_up_result_id', '=', $request->id)
                        ->where('item_id', '=', $value_item['item_id'])
                        ->first();

                    $res_value_item = $check_item_result->quantity;

                    $list_of_items = ListofItems::find($value_item['item_id']);

                    $count_item = $list_of_items->total_item + $res_value_item;

                    $list_of_items->total_item = $count_item;
                    $list_of_items->user_update_id = $request->user()->id;
                    $list_of_items->updated_at = \Carbon\Carbon::now();
                    $list_of_items->save();

                    $item_history = HistoryItemMovement::create([
                        'item_id' => $value_item['item_id'],
                        'quantity' => $res_value_item,
                        'status' => 'tambah',
                        'user_id' => $request->user()->id,
                    ]);

                    $detail_item->delete();

                } else {

                    //untuk cek quantity yang sudah ada untuk mencari selisih penambahan
                    $check_item_result = DB::table('detail_item_out_patients')
                        ->select('quantity')
                        ->where('check_up_result_id', '=', $request->id)
                        ->where('item_id', '=', $value_item['item_id'])
                        ->first();

                    if ($value_item['quantity'] > $check_item_result->quantity) {

                        $res_value_item = $value_item['quantity'] - $check_item_result->quantity;

                        $list_of_items = ListofItems::find($value_item['item_id']);

                        $count_item = $list_of_items->total_item - $res_value_item;

                        $list_of_items->total_item = $count_item;
                        $list_of_items->user_update_id = $request->user()->id;
                        $list_of_items->updated_at = \Carbon\Carbon::now();
                        $list_of_items->save();

                        $detail_item_out_patient = DetailItemOutPatient::find($value_item['id']);

                        $detail_item_out_patient->item_id = $value_item['item_id'];
                        $detail_item_out_patient->quantity = $value_item['quantity'];
                        $detail_item_out_patient->price_overall = $value_item['price_overall'];
                        $detail_item_out_patient->user_update_id = $request->user()->id;
                        $detail_item_out_patient->updated_at = \Carbon\Carbon::now();
                        $detail_item_out_patient->save();

                        $item_history = HistoryItemMovement::create([
                            'item_id' => $value_item['item_id'],
                            'quantity' => $res_value_item,
                            'status' => 'kurang',
                            'user_id' => $request->user()->id,
                        ]);

                    } elseif ($value_item['quantity'] < $check_item_result->quantity) {

                        $res_value_item = $check_item_result->quantity - $value_item['quantity'];

                        $list_of_items = ListofItems::find($value_item['item_id']);

                        $count_item = $list_of_items->total_item + $res_value_item;

                        $list_of_items->total_item = $count_item;
                        $list_of_items->user_update_id = $request->user()->id;
                        $list_of_items->updated_at = \Carbon\Carbon::now();
                        $list_of_items->save();

                        $detail_item_out_patient = DetailItemOutPatient::find($value_item['id']);

                        $detail_item_out_patient->item_id = $value_item['item_id'];
                        $detail_item_out_patient->quantity = $value_item['quantity'];
                        $detail_item_out_patient->price_overall = $value_item['price_overall'];
                        $detail_item_out_patient->user_update_id = $request->user()->id;
                        $detail_item_out_patient->updated_at = \Carbon\Carbon::now();
                        $detail_item_out_patient->save();

                        $item_history = HistoryItemMovement::create([
                            'item_id' => $value_item['item_id'],
                            'quantity' => $res_value_item,
                            'status' => 'tambah',
                            'user_id' => $request->user()->id,
                        ]);

                    } else {

                        $detail_item_out_patient = DetailItemOutPatient::find($value_item['id']);

                        $detail_item_out_patient->item_id = $value_item['item_id'];
                        $detail_item_out_patient->quantity = $value_item['quantity'];
                        $detail_item_out_patient->price_overall = $value_item['price_overall'];
                        $detail_item_out_patient->user_update_id = $request->user()->id;
                        $detail_item_out_patient->updated_at = \Carbon\Carbon::now();
                        $detail_item_out_patient->save();
                    }

                }
            }
        }

        return response()->json(
            [
                'message' => 'Ubah Data Berhasil!',
            ], 200
        );

    }

    public function delete(Request $request)
    {

        if ($request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $temp_item = $request->item;

        $result_item = json_decode(json_encode($temp_item), true);

        foreach ($result_item as $value_item) {

            $detail_item = DetailItemOutPatient::find($value_item['id']);

            $check_item_result = DB::table('detail_item_out_patients')
                ->select('quantity')
                ->where('check_up_result_id', '=', $request->id)
                ->where('item_id', '=', $value_item['item_id'])
                ->first();

            $res_value_item = $check_item_result->quantity;

            $list_of_items = ListofItems::find($value_item['item_id']);

            $count_item = $list_of_items->total_item + $res_value_item;

            $list_of_items->total_item = $count_item;
            $list_of_items->user_update_id = $request->user()->id;
            $list_of_items->updated_at = \Carbon\Carbon::now();
            $list_of_items->save();

            $item_history = HistoryItemMovement::create([
                'item_id' => $value_item['item_id'],
                'quantity' => $res_value_item,
                'status' => 'tambah',
                'user_id' => $request->user()->id,
            ]);

            $detail_item->delete();
        }

        $temp_services = $request->service;

        $services = json_decode(json_encode($temp_services), true);

        foreach ($services as $key_service) {

            $detail_service_out_patient = DetailServiceOutPatient::find($key_service['id']);
            $detail_service_out_patient->delete();
        }

        $check_up_result = CheckUpResult::find($request->id);
        $check_up_result->delete();

        return response()->json([
            'message' => 'Berhasil menghapus Data',
        ], 200);
    }
}
