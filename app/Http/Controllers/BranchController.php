<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        return view('cabang.index');
    }

    public function getDataBranch()
    {
        $branch = Branch::all();
        return response()->json($branch);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'KodeCabang' => 'required',
            'NamaCabang' => 'required',
        ]);

        Branch::create([
            'BranchCode' => $request->KodeCabang,
            'BranchName' => $request->NamaCabang,
        ]);

        return response()->json([
            'message' => 'Berhasil menambah Cabang',
        ], 200);
    }

    public function edit($id)
    {
        $branch = Branch::find($id);
        return view('cabang.edit', ['branch' => $branch]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'KodeCabang' => 'required',
            'NamaCabang' => 'required',
        ]);

        $branch = Branch::find($id);
        $branch->BranchCode = $request->KodeCabang;
        $branch->BranchName = $request->NamaCabang;
        $branch->save();
        return response()->json([
            'message' => 'Berhasil mengupdate Cabang',
        ], 200);
    }

    public function delete($id)
    {
        $branch = Branch::find($id);
        $branch->delete();
        return response()->json([
            'message' => 'Berhasil menghapus Cabang',
        ], 200);
    }
}
