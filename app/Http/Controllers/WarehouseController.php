<?php

namespace App\Http\Controllers;

use App\Exports\TemplateUploadGudang;
use App\Models\ListofItems;
use Illuminate\Http\Request;
use Validator;

class WarehouseController extends Controller
{
    public function create(Request $request)
    {
        if ($request->user()->role == 'kasir') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'item_name' => 'required|string|min:3|max:50',
            'total_item' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric',
            'capital_price' => 'required|numeric',
            'profit' => 'required|numeric',
            'image' => 'mimes:png,jpg|max:2048',
            'branch_id' => 'required|string',
            'category' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Data yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $check_list_item = ListofItems::where('item_name', 'like', '%' . $request->item_name . '%')
            ->where('branch_id', '=', $request->branch_id)
            ->where('category', '=', $request->category)
            ->count();

        if ($check_list_item > 0) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data yang dimasukkan sudah ada!'],
            ], 422);
        }

        if ($request->selling_price < $request->capital_price) {
            return response()->json([
                'message' => 'Data yang dimasukkan tidak valid!',
                'errors' => ['Data tidak valid!'],
            ], 422);
        }

        $file = "";

        if ($files = $request->file('image')) {
            $fileName = $request->image->hashName();

            $path = $request->file('image')->move(public_path("/documents"), $fileName);
            $photoURL = url('/' . $fileName);

            $file = "/documents/" . $fileName;
        }

        $item = ListofItems::create([
            'item_name' => $request->item_name,
            'total_item' => $request->total_item,
            'selling_price' => $request->selling_price,
            'capital_price' => $request->capital_price,
            'profit' => $request->profit,
            'image' => $file,
            'category' => $request->category,
            'branch_id' => $request->branch_id,
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
        if ($request->user()->role == 'kasir') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|min:1|max:1',
            'item_name' => 'required|string|min:3|max:50',
            'total_item' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric',
            'capital_price' => 'required|numeric',
            'profit' => 'required|numeric',
            'image' => 'mimes:png,jpg|max:2048',
            'branch_id' => 'required|string',
            'category' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Barang yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $check_item = ListofItems::where('id', '=', $request->id)
            ->count();

        if ($check_item == 0) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak ada ada!'],
            ], 422);
        }

        $file = "";

        if ($files = $request->file('image')) {
            $fileName = $request->image->hashName();

            $path = $request->file('image')->move(public_path("/documents"), $fileName);
            $photoURL = url('/' . $fileName);

            $file = "/documents/" . $fileName;
        }

        $insert_item = ListofItems::create([
            'item_name' => $request->item_name,
            'total_item' => $request->total_item,
            'selling_price' => $request->selling_price,
            'capital_price' => $request->capital_price,
            'profit' => $request->profit,
            'image' => $file,
            'category' => $request->category,
            'branch_id' => $request->branch_id,
            'user_id' => $request->user()->id,
        ]);

        $update_item = ListofItems::find($request->id);

        $update_item->user_update_id = $request->user()->id;
        $update_item->isDeleted = 1;
        $update_item->deleted_by = $request->user()->id;
        $update_item->updated_at = \Carbon\Carbon::now();
        $update_item->deleted_at = \Carbon\Carbon::now();
        $update_item->save();

        return response()->json([
            'message' => 'Berhasil mengubah Barang',
        ], 200);
    }

    public function delete(Request $request)
    {
        if ($request->user()->role == 'kasir') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $item = ListofItems::where('id', '=', $request->id)
            ->count();

        if ($item == 0) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak ada ada!'],
            ], 422);
        }

        $item->user_update_id = $request->user()->id;
        $item->isDeleted = 1;
        $item->deleted_by = $request->user()->id;
        $item->updated_at = \Carbon\Carbon::now();
        $item->deleted_at = \Carbon\Carbon::now();
        $item->save();

        return response()->json([
            'message' => 'Berhasil menghapus Barang',
        ], 200);
    }

    public function download_template_excel(Request $request)
    {
        if ($request->user()->role == 'kasir') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        if (is_null($request->filename)) {
            return response()->json([
                'message' => 'Data tidak valid!',
                'errors' => ['Nama file tidak ada'],
            ], 422);
        }

        return (new TemplateUploadGudang())->download($request->filename);
    }
    public function upload_excel(Request $request)
    {
        if ($request->user()->role == 'kasir') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

    }

    public function download_result_excel(Request $request)
    {
        if ($request->user()->role == 'kasir') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }
    }
}
