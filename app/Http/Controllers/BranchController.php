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

    public function getDataBranch() {
        $branch = Branch::all();
        return response()->json($branch);
    }

    public function tambah()
    {
        return view('cabang.create');
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

        return redirect('/cabang');
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
        return redirect('/cabang');
    }

    public function delete($id)
    {
        $branch = Branch::find($id);
        $branch->delete();
        return redirect('/cabang');
    }
}
