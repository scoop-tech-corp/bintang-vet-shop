<?php

namespace App\Http\Controllers;

use App\Models\CategoryGoods;
use DB;
use Illuminate\Http\Request;
use Validator;

class KategoriBarangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        if ($request->orderby == 'asc') {

            $category_goods = DB::table('category_goods')
                ->select('id', 'category_name', 'created_by',
                    DB::raw("DATE_FORMAT(created_at, '%d %b %Y') as created_at"))
                    ->where('isDeleted', '=', 'false')
                    ->orderBy($request->column, 'asc')->get();

        } else if ($request->orderby == 'desc') {

            $category_goods = DB::table('category_goods')
                ->select('id', 'category_name', 'created_by',
                    DB::raw("DATE_FORMAT(created_at, '%d %b %Y') as created_at"))
                    ->where('isDeleted', '=', 'false')
                    ->orderBy($request->column, 'desc')->get();

        } else {

            $category_goods = DB::table('category_goods')
                ->select('id', 'category_name', 'created_by',
                    DB::raw("DATE_FORMAT(created_at, '%d %b %Y') as created_at"))
                    ->where('isDeleted', '=', 'false')
                    ->get();

        }

        return response()->json($category_goods, 200);
    }

    public function create(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $validate = Validator::make($request->all(), [
            'NamaKategori' => 'required|string|max:50|unique:category_goods,category_name',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        CategoryGoods::create([
            'category_name' => $request->NamaKategori,
            'created_by' => $request->user()->fullname,
        ]);

        return response()->json([
            'message' => 'Berhasil menambah Kategori Barang',
        ], 200);
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
            'NamaKategori' => 'required|string|max:50',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        $category_goods = CategoryGoods::find($request->id);

        if (is_null($category_goods)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $find_duplicate = db::table('category_goods')
            ->select('category_name')
            ->where('category_name', 'like', '%' . $request->NamaKategori . '%')
            ->where('id', '!=', $request->id)
            ->count();

        if ($find_duplicate != 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data duplicate!'],
            ], 422);

        }

        $category_goods->category_name = $request->NamaKategori;
        $category_goods->update_by = $request->user()->fullname;
        $category_goods->updated_at = \Carbon\Carbon::now();
        $category_goods->save();

        return response()->json([
            'message' => 'Berhasil mengupdate Kategori Barang',
        ], 200);
    }

    public function delete(Request $request)
    {
        $category_goods = CategoryGoods::find($request->id);

        if (is_null($category_goods)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $category_goods->isDeleted = true;
        $category_goods->deleted_by = $request->user()->fullname;
        $category_goods->deleted_at = \Carbon\Carbon::now();
        $category_goods->save();

        $category_goods->delete();

        return response()->json([
            'message' => 'Berhasil menghapus Kategori Barang',
        ], 200);
    }

    public function search(Request $request)
    {

    }
}
