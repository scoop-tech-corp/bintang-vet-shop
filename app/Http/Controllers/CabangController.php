<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class CabangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $branch = Branch::select('id', 'branch_code', 'branch_name','created_by','created_at')->get();

        return response()->json($branch, 200);
    }

    public function create(Request $request)
    {

        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $this->validate($request, [
            'KodeCabang' => 'required|max:5|unique:branches,branch_code',
            'NamaCabang' => 'required|max:20|unique:branches,branch_name',
        ]);

        Branch::create([
            'branch_code' => $request->KodeCabang,
            'branch_name' => $request->NamaCabang,
            'created_by' => $request->user()->fullname,
        ]);

        return response()->json([
            'message' => 'Berhasil menambah Cabang',
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
            'KodeCabang' => 'required|max:5', //|unique:branches,branch_code
            'NamaCabang' => 'required|max:20',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 401);
        }

        $branch = Branch::find($request->id);
        
        if (is_null($branch)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $branch->branch_code = $request->KodeCabang;
        $branch->branch_name = $request->NamaCabang;
        $branch->update_by = $request->user()->fullname;
        $branch->updated_at = \Carbon\Carbon::now();
        $branch->save();

        return response()->json([
            'message' => 'Berhasil mengupdate Cabang',
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

        $branch = Branch::find($request->id);

        if (is_null($branch)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $branch->isDeleted = true;
        $branch->deleted_by = $request->user()->fullname;
        $branch->deleted_at = \Carbon\Carbon::now();
        $branch->save();

        $branch->delete();

        return response()->json([
            'message' => 'Berhasil menghapus Cabang',
        ], 200);
    }
}
