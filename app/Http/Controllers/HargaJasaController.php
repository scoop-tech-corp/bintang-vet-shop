<?php

namespace App\Http\Controllers;

use App\Models\PriceService;
use DB;
use Illuminate\Http\Request;
use Validator;

class HargaJasaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $price_services = DB::table('price_services')
            ->join('users', 'price_services.user_id', '=', 'users.id')
            ->join('list_of_services', 'price_services.list_of_services_id', '=', 'list_of_services.id')
            ->join('branches', 'list_of_services.branch_id', '=', 'branches.id')
            ->join('service_categories', 'list_of_services.service_category_id', '=', 'service_categories.id')
            ->select('price_services.id', 'list_of_services.id as service_name_id', 'list_of_services.service_name',
                'service_categories.id as service_categories_id', 'service_categories.category_name',
                'branches.id as branch_id', 'branches.branch_name', 'price_services.selling_price',
                'price_services.capital_price', 'price_services.doctor_fee', 'price_services.petshop_fee',
                'users.fullname as created_by', DB::raw("DATE_FORMAT(price_services.created_at, '%d %b %Y') as created_at"));

        if ($request->branch_id && $request->user()->role == 'admin') {
            $price_services = $price_services->where('branches.id', '=', $request->branch_id);
        }

        if ($request->user()->role == 'dokter') {
            $price_services = $price_services->where('branches.id', '=', $request->user()->branch_id);
        }

        if ($request->keyword) {

            $price_services = $price_services
                ->where('list_of_services.service_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('service_categories.category_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('branches.branch_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%')
                ->orwhere('price_services.created_at', 'like', '%' . $request->keyword . '%');
        }

        if ($request->orderby) {
            $price_services = $price_services->orderBy($request->column, $request->orderby);
        }

        $price_services = $price_services->orderBy('price_services.id', 'asc');

        $price_services = $price_services->get();

        return response()->json($price_services, 200);
    }

    public function create(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'ListOfServiceId' => 'required|numeric',
            'HargaJual' => 'required|numeric|min:0',
            'HargaModal' => 'required|numeric|min:0',
            'FeeDokter' => 'required|numeric|min:0',
            'FeePetShop' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Data yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $check_list_item = DB::table('price_services')
            ->where('list_of_services_id', '=', $request->ListOfServiceId)
            ->count();

        if ($check_list_item > 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data duplicate!'],
            ], 422);
        }

        $item = PriceService::create([
            'list_of_services_id' => $request->ListOfServiceId,
            'selling_price' => $request->HargaJual,
            'capital_price' => $request->HargaModal,
            'doctor_fee' => $request->FeeDokter,
            'petshop_fee' => $request->FeePetShop,
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
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'ListOfServiceId' => 'required|numeric',
            'HargaJual' => 'required|numeric|min:0',
            'HargaModal' => 'required|numeric|min:0',
            'FeeDokter' => 'required|numeric|min:0',
            'FeePetShop' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Data yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $price_services = PriceService::find($request->id);

        if (is_null($price_services)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $check_list_item = DB::table('price_services')
            ->where('list_of_services_id', '=', $request->ListOfServiceId)
            ->where('id', '!=', $request->id)
            ->count();

        if ($check_list_item > 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data duplicate!'],
            ], 422);
        }

        $price_services->list_of_services_id = $request->ListOfServiceId;
        $price_services->selling_price = $request->HargaJual;
        $price_services->capital_price = $request->HargaModal;
        $price_services->doctor_fee = $request->FeeDokter;
        $price_services->petshop_fee = $request->FeePetShop;
        $price_services->user_update_id = $request->user()->id;
        $price_services->updated_at = \Carbon\Carbon::now();
        $price_services->save();

        return response()->json([
            'message' => 'Berhasil mengubah Data',
        ], 200);
    }

    public function delete(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $price_services = PriceService::find($request->id);

        if (is_null($price_services)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $price_services->isDeleted = true;
        $price_services->deleted_by = $request->user()->fullname;
        $price_services->deleted_at = \Carbon\Carbon::now();
        $price_services->save();

        $price_services->delete();

        return response()->json([
            'message' => 'Berhasil menghapus Data',
        ], 200);
    }

    public function service_category(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $list_of_services = DB::table('list_of_services')
            ->join('service_categories', 'list_of_services.service_category_id', '=', 'service_categories.id')
            ->select('service_category_id', 'service_categories.category_name')
            ->where('branch_id', '=', $request->branch_id)
            ->distinct('service_category_id')
            ->get();

        if (is_null($list_of_services)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        return response()->json($list_of_services, 200);
    }

    public function service_name(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $list_of_services = DB::table('list_of_services')
            ->join('service_categories', 'list_of_services.service_category_id', '=', 'service_categories.id')
            ->select('list_of_services.id', 'list_of_services.service_name')
            ->where('branch_id', '=', $request->branch_id)
            ->where('service_category_id', '=', $request->service_category_id)
            ->get();

        if (is_null($list_of_services)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        return response()->json($list_of_services, 200);
    }
}
