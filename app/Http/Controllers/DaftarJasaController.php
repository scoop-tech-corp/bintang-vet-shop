<?php

namespace App\Http\Controllers;

use App\Models\ListofServices;
use DB;
use Illuminate\Http\Request;
use Validator;

class DaftarJasaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $list_of_services = DB::table('list_of_services')
            ->join('users', 'list_of_services.user_id', '=', 'users.id')
            ->join('branches', 'list_of_services.branch_id', '=', 'branches.id')
            ->join('service_categories', 'list_of_services.service_category_id', '=', 'service_categories.id')
            ->select('list_of_services.id', 'list_of_services.service_name', 'list_of_services.service_category_id', 'service_categories.category_name'
                , 'list_of_services.branch_id', 'branches.branch_name', 'users.fullname as created_by',
                DB::raw("DATE_FORMAT(list_of_services.created_at, '%d %b %Y') as created_at"));

        if ($request->branch_id && $request->user()->role == 'admin') {
            $list_of_services = $list_of_services->where('list_of_services.branch_id', '=', $request->branch_id);
        }

        if ($request->user()->role == 'dokter') {
            $list_of_services = $list_of_services->where('list_of_services.branch_id', '=', $request->user()->branch_id);
        }

        if ($request->keyword) {

            $list_of_services = $list_of_services->where('list_of_services.service_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('service_categories.category_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('branches.branch_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%');
        }

        if ($request->orderby) {
            $list_of_services = $list_of_services->orderBy($request->column, $request->orderby);
        }

        $list_of_services = $list_of_services->orderBy('list_of_services.id', 'desc');

        $list_of_services = $list_of_services->get();

        return response()->json($list_of_services, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NamaLayanan' => 'required|string|min:3|max:50',
            'KategoriJasa' => 'required|integer|max:50',
            'CabangId' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Jasa yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $check_service = DB::table('list_of_services')
            ->where('branch_id', '=', $request->CabangId)
            ->where('service_category_id', '=', $request->KategoriJasa)
            ->where('service_name', '=', $request->NamaLayanan)
            ->count();

        if ($check_service > 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data sudah pernah ada sebelumnya!'],
            ], 422);
        }

        $list_of_services = ListofServices::create([
            'service_name' => $request->NamaLayanan,
            'service_category_id' => $request->KategoriJasa,
            'branch_id' => $request->CabangId,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(
            [
                'message' => 'Tambah Jasa Berhasil!',
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

        $validate = Validator::make($request->all(), [
            'NamaLayanan' => 'required|string|min:3|max:50',
            'KategoriJasa' => 'required|integer|max:50',
            'CabangId' => 'required|integer',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        $list_of_services = ListofServices::find($request->id);

        if (is_null($list_of_services)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $find_duplicate = db::table('list_of_services')
            ->where('branch_id', '=', $request->cabang)
            ->where('service_name', '=', $request->nama_barang)
            ->where('id', '!=', $request->id)
            ->count();

        if ($find_duplicate != 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data duplicate!'],
            ], 422);

        }

        $list_of_services->service_name = $request->NamaLayanan;
        $list_of_services->service_category_id = $request->KategoriJasa;
        $list_of_services->branch_id = $request->CabangId;
        $list_of_services->user_update_id = $request->user()->id;
        $list_of_services->updated_at = \Carbon\Carbon::now();
        $list_of_services->save();

        return response()->json([
            'message' => 'Berhasil mengubah Jasa',
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

        $list_of_services = ListofServices::find($request->id);

        if (is_null($list_of_services)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $list_of_services->isDeleted = true;
        $list_of_services->deleted_by = $request->user()->fullname;
        $list_of_services->deleted_at = \Carbon\Carbon::now();
        $list_of_services->save();

        $list_of_services->delete();

        return response()->json([
            'message' => 'Berhasil menghapus Barang',
        ], 200);
    }
}
