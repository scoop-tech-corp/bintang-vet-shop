<?php

namespace App\Http\Controllers;

use App\Models\MedicineGroup;
use DB;
use Illuminate\Http\Request;
use Validator;

class KelompokObatController extends Controller
{
    public function index(Request $request)
    {
        $medicine_groups = DB::table('medicine_groups')
            ->join('users', 'medicine_groups.user_id', '=', 'users.id')
            ->join('branches', 'medicine_groups.branch_id', '=', 'users.id')
            ->select('medicine_groups.id','branches.branch_name', 'group_name', 'users.fullname as created_by',
                DB::raw("DATE_FORMAT(medicine_groups.created_at, '%d %b %Y') as created_at"));

        if ($request->keyword) {
            $medicine_groups = $medicine_groups->where('group_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('created_by', 'like', '%' . $request->keyword . '%');
        }

        if ($request->branch_id && $request->user()->role == 'admin') {
            $medicine_groups = $medicine_groups->where('branches.id', '=', $request->branch_id);
        }

        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            $medicine_groups = $medicine_groups->where('branches.id', '=', $request->user()->branch_id);
        }

        if ($request->orderby) {
            $medicine_groups = $medicine_groups->orderBy($request->column, $request->orderby);
        }

        $medicine_groups = $medicine_groups->orderBy('medicine_groups.id', 'desc');

        $medicine_groups = $medicine_groups->get();

        return response()->json($medicine_groups, 200);
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
            'NamaGrup' => 'required|string|max:50',
            'Cabang' => 'required|integer',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        $find_duplicate = db::table('medicine_groups')
            ->select('group_name')
            ->where('group_name', 'like', '%' . $request->NamaGrup . '%')
            ->where('branch_id', '=', $request->Cabang)
            ->count();

        if ($find_duplicate != 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data sudah ada!'],
            ], 422);

        }

        MedicineGroup::create([
            'group_name' => $request->NamaGrup,
            'branch_id' => $request->Cabang,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Berhasil menambah Kelompok Barang',
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
            'NamaGrup' => 'required|string|max:50',
            'Cabang' => 'required|integer',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        $medicine_groups = MedicineGroup::find($request->id);

        if (is_null($medicine_groups)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak ditemukan!'],
            ], 404);
        }

        $find_duplicate = db::table('medicine_groups')
            ->select('group_name')
            ->where('group_name', 'like', '%' . $request->NamaGrup . '%')
            ->where('branch_id', '=', $request->Cabang)
            ->where('id', '!=', $request->id)
            ->count();

        if ($find_duplicate != 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data sudah ada!'],
            ], 422);

        }

        $medicine_groups->group_name = $request->NamaGrup;
        $medicine_groups->branch_id = $request->Cabang;
        $medicine_groups->user_update_id = $request->user()->id;
        $medicine_groups->updated_at = \Carbon\Carbon::now();
        $medicine_groups->save();

        return response()->json([
            'message' => 'Berhasil mengupdate Kelompok Obat',
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

        $medicine_groups = MedicineGroup::find($request->id);

        if (is_null($medicine_groups)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak ditemukan!'],
            ], 404);
        }

        $medicine_groups->isDeleted = true;
        $medicine_groups->deleted_by = $request->user()->fullname;
        $medicine_groups->deleted_at = \Carbon\Carbon::now();
        $medicine_groups->save();

        $medicine_groups->delete();

        return response()->json([
            'message' => 'Berhasil menghapus Kategori Barang',
        ], 200);
    }
}
