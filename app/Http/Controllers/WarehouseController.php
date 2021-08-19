<?php

namespace App\Http\Controllers;

use App\Exports\RecapWarehouse;
use App\Exports\TemplateUploadGudang;
use App\Imports\UploadItem;
use App\Models\ListofItems;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $item = DB::table('list_of_items')
            ->join('users', 'list_of_items.user_id', '=', 'users.id')
            ->join('branches', 'list_of_items.branch_id', '=', 'branches.id')
            ->select(
                'list_of_items.id',
                'list_of_items.item_name',
                'list_of_items.total_item',
                DB::raw("TRIM(list_of_items.selling_price)+0 as selling_price"),
                DB::raw("TRIM(list_of_items.capital_price)+0 as capital_price"),
                DB::raw("TRIM(list_of_items.profit)+0 as profit"),
                'list_of_items.image',
                'branches.id as branch_id',
                'branches.branch_name',
                'users.id as user_id',
                'users.fullname as created_by',
                DB::raw("DATE_FORMAT(list_of_items.created_at, '%d %b %Y') as created_at"))
            ->where('list_of_items.isDeleted', '=', 0)
            ->where('list_of_items.category', '=', $request->category);

        if ($request->branch_id && $request->user()->role == 'admin') {
            $item = $item->where('list_of_items.branch_id', '=', $request->branch_id);
        }

        if ($request->user()->role == 'kasir') {
            $item = $item->where('list_of_items.branch_id', '=', $request->user()->branch_id);
        }

        if ($request->keyword) {

            $item = $item->where('list_of_items.item_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('branches.branch_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%');
        }

        if ($request->orderby) {
            $item = $item->orderBy($request->column, $request->orderby);
        }

        $item = $item->orderBy('list_of_items.id', 'desc');

        $item = $item->get();

        return response()->json($item, 200);
    }

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

        $check_list_item = ListofItems::where('item_name', '=', $request->item_name)
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
            'id' => 'required|numeric',
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

        $update_item = ListofItems::find($request->id);

        if ($file == "") {
          $file = $update_item->image;
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

        $item = ListofItems::find($request->id);

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

    public function download_report_excel(Request $request)
    {
        if ($request->user()->role == 'kasir') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        return (new RecapWarehouse($request->orderby, $request->column, $request->date, $branch, $request->category))->download('Laporan Keuangan Harian.xlsx');
    }

    public function upload_excel(Request $request)
    {
        if ($request->user()->role == 'kasir') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx',
            'category' => 'required|string',
        ]);

        $rows = Excel::toArray(new UploadItem($request->category), $request->file('file'));
        $result = $rows[0];

        foreach ($result as $key_result) {

            $check_duplicate = DB::table('list_of_items')
                ->where('item_name', 'like', '%' . $key_result['nama_barang'] . '%')
                ->where('category', '=', $request->category)
                ->where('branch_id', '=', $key_result['kode_cabang'])
                ->count();

            if ($check_duplicate > 0) {

                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Terdapat Data yang sudah ada!'],
                ], 422);
            }

            if ($key_result['harga_modal'] > $key_result['harga_jual']) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Harga Modal harus lebih kecil dengan Harga Jual!'],
                ], 422);
            }

            $file = $request->file('file');

            Excel::import(new UploadItem($request->category), $file);

            return response()->json([
                'message' => 'Berhasil mengupload Barang',
            ], 200);
        }

    }
}
