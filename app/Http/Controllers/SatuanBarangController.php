<?php

namespace App\Http\Controllers;

use App\Models\UnitGoods;
use DB;
use Illuminate\Http\Request;
use Validator;

class SatuanBarangController extends Controller
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

            $unit_goods = DB::table('unit_goods')
                ->select('id', 'unit_name', 'created_by',
                    DB::raw("DATE_FORMAT(created_at, '%d %b %Y') as created_at"))
                ->where('isDeleted', '=', 'false')
                ->orderBy($request->column, 'asc')->get();

        } else if ($request->orderby == 'desc') {

            $unit_goods = DB::table('unit_goods')
                ->select('id', 'unit_name', 'created_by',
                    DB::raw("DATE_FORMAT(created_at, '%d %b %Y') as created_at"))
                ->where('isDeleted', '=', 'false')
                ->orderBy($request->column, 'desc')->get();

        } else {

            $unit_goods = DB::table('unit_goods')
                ->select('id', 'unit_name', 'created_by',
                    DB::raw("DATE_FORMAT(created_at, '%d %b %Y') as created_at"))
                ->where('isDeleted', '=', 'false')
                ->get();

        }

        return response()->json($unit_goods, 200);

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
            'NamaSatuan' => 'required|string|max:50|unique:unit_goods,unit_name',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        UnitGoods::create([
            'unit_name' => $request->NamaSatuan,
            'created_by' => $request->user()->fullname,
        ]);

        return response()->json([
            'message' => 'Berhasil menambah Unit Barang',
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
            'NamaSatuan' => 'required|string|max:50',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        $unit_goods = UnitGoods::find($request->id);

        if (is_null($unit_goods)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $find_duplicate = db::table('unit_goods')
            ->select('unit_name')
            ->where('unit_name', '=', $request->NamaSatuan)
            ->where('id', '!=', $request->id)
            ->count();

        if ($find_duplicate != 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data duplicate!'],
            ], 422);

        }

        $unit_goods->unit_name = $request->NamaSatuan;
        $unit_goods->update_by = $request->user()->fullname;
        $unit_goods->updated_at = \Carbon\Carbon::now();
        $unit_goods->save();

        return response()->json([
            'message' => 'Berhasil mengupdate Unit Barang',
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

        $unit_goods = UnitGoods::find($request->id);

        if (is_null($unit_goods)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $unit_goods->isDeleted = true;
        $unit_goods->deleted_by = $request->user()->fullname;
        $unit_goods->deleted_at = \Carbon\Carbon::now();
        $unit_goods->save();

        $unit_goods->delete();

        return response()->json([
            'message' => 'Berhasil menghapus Unit Barang',
        ], 200);
    }

    public function search(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $unit_goods = DB::table('unit_goods')
            ->select('id', 'unit_name', 'created_by',
                DB::raw("DATE_FORMAT(created_at, '%d %b %Y') as created_at"))
            ->where('isDeleted', '=', 'false')
            ->where('unit_name', 'like', '%' . $request->keyword . '%')
            ->orwhere('created_by', 'like', '%' . $request->keyword . '%')
            ->get();

        return response()->json($unit_goods, 200);
    }
}
