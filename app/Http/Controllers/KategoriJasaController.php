<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use DB;
use Illuminate\Http\Request;
use Validator;

class KategoriJasaController extends Controller
{
    public function index(Request $request)
    {
        $service_categories = DB::table('service_categories')
            ->join('users', 'service_categories.user_id', '=', 'users.id')
            ->select('service_categories.id', 'category_name', 'users.fullname as created_by',
                DB::raw("DATE_FORMAT(service_categories.created_at, '%d %b %Y') as created_at"))
                ->where('service_categories.isDeleted', '=', 0);

        if ($request->keyword) {
            $service_categories = $service_categories->where('category_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%');
        }

        if ($request->orderby) {
            $service_categories = $service_categories->orderBy($request->column, $request->orderby);
        }

        $service_categories = $service_categories->orderBy('service_categories.id', 'desc');

        $service_categories = $service_categories->get();

        return response()->json($service_categories, 200);
    }

    public function create(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $validate = Validator::make($request->all(), [
            'NamaKategoriJasa' => 'required|string|max:50|unique:service_categories,category_name',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        ServiceCategory::create([
            'category_name' => $request->NamaKategoriJasa,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Berhasil menambah Kategori Jasa',
        ], 200);
    }

    public function update(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $validate = Validator::make($request->all(), [
            'NamaKategoriJasa' => 'required|string|max:50',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        $service_categories = ServiceCategory::find($request->id);

        if (is_null($service_categories)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak ditemukan!'],
            ], 404);
        }

        $find_duplicate = db::table('service_categories')
            ->select('category_name')
            ->where('category_name', 'like', '%' . $request->NamaKategoriJasa . '%')
            ->where('id', '!=', $request->id)
            ->count();

        if ($find_duplicate != 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data sudah ada!'],
            ], 422);

        }

        $service_categories->category_name = $request->NamaKategoriJasa;
        $service_categories->user_update_id = $request->user()->id;
        $service_categories->updated_at = \Carbon\Carbon::now();
        $service_categories->save();

        return response()->json([
            'message' => 'Berhasil mengupdate Kategori Jasa',
        ], 200);
    }

    public function delete(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $service_categories = ServiceCategory::find($request->id);

        if (is_null($service_categories)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak ditemukan!'],
            ], 404);
        }

        $service_categories->isDeleted = true;
        $service_categories->deleted_by = $request->user()->fullname;
        $service_categories->deleted_at = \Carbon\Carbon::now();
        $service_categories->save();

        return response()->json([
            'message' => 'Berhasil menghapus Kategori Jasa',
        ], 200);
    }
}
