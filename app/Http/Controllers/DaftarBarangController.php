<?php

namespace App\Http\Controllers;

use App\Models\ListofItems;
use DB;
use Illuminate\Http\Request;
use Validator;

class DaftarBarangController extends Controller
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

            $item = DB::table('list_of_items')
                ->join('branches', 'list_of_items.branch_id', '=', 'branches.id')
                ->join('unit_goods', 'list_of_items.unit_goods_id', '=', 'unit_goods.id')
                ->join('category_goods', 'list_of_items.category_goods_id', '=', 'category_goods.id')
                ->select('list_of_items.id', 'list_of_items.item_name', 'list_of_items.total_item',
                    'unit_goods.id', 'unit_goods.unit_name', 'category_goods.id', 'category_goods.category_name'
                    , 'branches.id', 'branches.branch_name', 'list_of_items.created_by',
                    DB::raw("DATE_FORMAT(list_of_items.created_at, '%d %b %Y') as created_at"))
                ->orderBy($request->column, 'asc')
                ->get();

        } else if ($request->orderby == 'desc') {

            $item = DB::table('list_of_items')
                ->join('branches', 'list_of_items.branch_id', '=', 'branches.id')
                ->join('unit_goods', 'list_of_items.unit_goods_id', '=', 'unit_goods.id')
                ->join('category_goods', 'list_of_items.category_goods_id', '=', 'category_goods.id')
                ->select('list_of_items.id', 'list_of_items.item_name', 'list_of_items.total_item',
                    'unit_goods.id', 'unit_goods.unit_name', 'category_goods.id', 'category_goods.category_name'
                    , 'branches.id', 'branches.branch_name', 'list_of_items.created_by',
                    DB::raw("DATE_FORMAT(list_of_items.created_at, '%d %b %Y') as created_at"))
                ->orderBy($request->column, 'desc')
                ->get();
        } else {

            $item = DB::table('list_of_items')
                ->join('branches', 'list_of_items.branch_id', '=', 'branches.id')
                ->join('unit_goods', 'list_of_items.unit_goods_id', '=', 'unit_goods.id')
                ->join('category_goods', 'list_of_items.category_goods_id', '=', 'category_goods.id')
                ->select('list_of_items.id', 'list_of_items.item_name', 'list_of_items.total_item',
                    'unit_goods.id', 'unit_goods.unit_name', 'category_goods.id', 'category_goods.category_name'
                    , 'branches.id', 'branches.branch_name', 'list_of_items.created_by',
                    DB::raw("DATE_FORMAT(list_of_items.created_at, '%d %b %Y') as created_at"))
                ->get();
        }

        return response()->json($item, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|min:3|max:50',
            'jumlah_barang' => 'required|numeric|min:1',
            'satuan_barang' => 'required|string|max:50',
            'kategori_barang' => 'required|string|max:50',
            'cabang' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Pasien yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $check_branch = DB::table('list_of_items')
            ->where('branch_id', '=', $request->cabang)
            ->where('item_name', '=', $request->nama_barang)
            ->count();

        if ($check_branch > 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data duplicate!'],
            ], 422);
        }

        $item = ListofItems::create([
            'item_name' => $request->nama_barang,
            'total_item' => $request->jumlah_barang,
            'unit_goods_id' => $request->satuan_barang,
            'category_goods_id' => $request->kategori_barang,
            'branch_id' => $request->cabang,
            'created_by' => $request->user()->fullname,
        ]);

        return response()->json(
            [
                'message' => 'Tambah Daftar Barang Berhasil!',
            ], 200
        );
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|min:3|max:50',
            'jumlah_barang' => 'required|numeric|min:1',
            'satuan_barang' => 'required|string|max:50',
            'kategori_barang' => 'required|string|max:50',
            'cabang' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Barang yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $item = ListofItems::find($request->id);

        if (is_null($item)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $find_duplicate = db::table('list_of_items')
            ->where('branch_id', '=', $request->cabang)
            ->where('item_name', '=', $request->nama_barang)
            ->where('id', '!=', $request->id)
            ->count();

        if ($find_duplicate != 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data duplicate!'],
            ], 422);

        }

        $item->item_name = $request->nama_barang;
        $item->total_item = $request->jumlah_barang;
        $item->unit_goods_id = $request->satuan_barang;
        $item->category_goods_id = $request->kategori_barang;
        $item->branch_id = $request->cabang;
        $item->update_by = $request->user()->fullname;
        $item->updated_at = \Carbon\Carbon::now();
        $item->save();

        return response()->json([
            'message' => 'Berhasil mengupdate Barang',
        ], 200);
    }

    public function delete(Request $request)
    {
        $item = ListofItems::find($request->id);

        if (is_null($item)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $item->isDeleted = true;
        $item->deleted_by = $request->user()->fullname;
        $item->deleted_at = \Carbon\Carbon::now();
        $item->save();

        $item->delete();

        return response()->json([
            'message' => 'Berhasil menghapus Barang',
        ], 200);
    }

    public function search(Request $request)
    {
        $item = DB::table('list_of_items')
            ->join('branches', 'list_of_items.branch_id', '=', 'branches.id')
            ->join('unit_goods', 'list_of_items.unit_goods_id', '=', 'unit_goods.id')
            ->join('category_goods', 'list_of_items.category_goods_id', '=', 'category_goods.id')
            ->select('list_of_items.id', 'list_of_items.item_name', 'list_of_items.total_item',
                'unit_goods.unit_name', 'category_goods.category_name'
                , 'branches.branch_name', 'list_of_items.created_by',
                DB::raw("DATE_FORMAT(list_of_items.created_at, '%d %b %Y') as created_at"))
            ->where('list_of_items.item_name', 'like', '%' . $request->keyword . '%')
            ->orwhere('list_of_items.total_item', 'like', '%' . $request->keyword . '%')
            ->orwhere('unit_goods.unit_name', 'like', '%' . $request->keyword . '%')
            ->orwhere('category_goods.category_name', 'like', '%' . $request->keyword . '%')
            ->orwhere('branches.branch_name', 'like', '%' . $request->keyword . '%')
            ->orwhere('list_of_items.created_by', 'like', '%' . $request->keyword . '%')
            ->get();

        return response()->json($item, 200);
    }

    public function download_template()
    {
        return response()->download(public_path('source_download/cobadownload.xlsx'), 'Template Excel');
    }

    public function upload_template(Request $request)
    {
        # code...
    }
}
