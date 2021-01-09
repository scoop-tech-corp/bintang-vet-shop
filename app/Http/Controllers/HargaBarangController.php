<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Validator;
use App\Models\PriceItem;

class HargaBarangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $price_items = DB::table('price_items')
            ->join('users', 'price_items.user_id', '=', 'users.id')
            ->join('list_of_items', 'price_items.list_of_items_id', '=', 'list_of_items.id')
            ->join('unit_item', 'list_of_items.unit_item_id', '=', 'unit_item.id')
            ->join('category_item', 'list_of_items.category_item_id', '=', 'category_item.id')
            ->join('branches', 'list_of_items.branch_id', '=', 'branches.id')
            ->select('price_items.id', 'list_of_items.id as item_name_id', 'list_of_items.item_name',
                'category_item.id as item_categories_id', 'category_item.category_name',
                'list_of_items.unit_item_id as unit_item_id', 'unit_item.unit_name', 'list_of_items.total_item',
                'branches.id as branch_id', 'branches.branch_name', 'price_items.selling_price',
                'price_items.capital_price', 'price_items.doctor_fee', 'price_items.petshop_fee',
                'users.fullname as created_by', DB::raw("DATE_FORMAT(price_items.created_at, '%d %b %Y') as created_at"));

        if ($request->keyword) {

            $price_items = $price_items
                ->where('list_of_items.item_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('category_item.category_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('branches.branch_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%')
                ->orwhere('price_items.created_at', 'like', '%' . $request->keyword . '%');
        }

        if ($request->orderby) {
            $price_items = $price_items->orderBy($request->column, $request->orderby);
        }

        $price_items = $price_items->orderBy('price_items.id', 'asc');

        $price_items = $price_items->get();

        return response()->json($price_items, 200);
    }

    public function create(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'ListOfItemsId' => 'required|numeric',
            'HargaJual' => 'required|numeric|min:0',
            'HargaModal' => 'required|numeric|min:0',
            'FeeDokter' => 'required|numeric|min:0',
            'FeePetShop' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Data yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $check_list_item = DB::table('price_items')
            ->where('list_of_items_id', '=', $request->ListOfServiceId)
            ->count();

        if ($check_list_item > 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data duplicate!'],
            ], 422);
        }

        $item = PriceItem::create([
            'list_of_items_id' => $request->ListOfItemsId,
            'selling_price' => $request->HargaJual,
            'capital_price' => $request->HargaModal,
            'doctor_fee' => $request->FeeDokter,
            'petshop_fee' => $request->FeePetShop,
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
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'ListOfItemsId' => 'required|numeric',
            'HargaJual' => 'required|numeric|min:0',
            'HargaModal' => 'required|numeric|min:0',
            'FeeDokter' => 'required|numeric|min:0',
            'FeePetShop' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Data yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $price_items = PriceItem::find($request->id);

        if (is_null($price_items)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $check_list_item = DB::table('price_items')
            ->where('list_of_items_id', '=', $request->ListOfItemsId)
            ->where('id', '!=', $request->id)
            ->count();

        if ($check_list_item > 0) {

            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data duplicate!'],
            ], 422);
        }
        
        $price_items->list_of_items_id = $request->ListOfItemsId;
        $price_items->selling_price = $request->HargaJual;
        $price_items->capital_price = $request->HargaModal;
        $price_items->doctor_fee = $request->FeeDokter;
        $price_items->petshop_fee = $request->FeePetShop;
        $price_items->user_update_id = $request->user()->id;
        $price_items->updated_at = \Carbon\Carbon::now();
        $price_items->save();

        return response()->json([
            'message' => 'Berhasil mengubah Data',
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

        $price_items = PriceItem::find($request->id);

        if (is_null($price_items)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $price_items->isDeleted = true;
        $price_items->deleted_by = $request->user()->fullname;
        $price_items->deleted_at = \Carbon\Carbon::now();
        $price_items->save();

        $price_items->delete();

        return response()->json([
            'message' => 'Berhasil menghapus Data',
        ], 200);
    }

    public function item_category(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $list_of_item = DB::table('list_of_items')
            ->join('category_item', 'list_of_items.category_item_id', '=', 'category_item.id')
            ->select('category_item_id', 'category_item.category_name')
            ->where('branch_id', '=', $request->branch_id)
            ->distinct('category_item_id')
            ->get();

        if (is_null($list_of_item)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        return response()->json($list_of_item, 200);
    }

    public function item_name(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $list_of_items = DB::table('list_of_items')
            ->join('category_item', 'list_of_items.category_item_id', '=', 'category_item.id')
            ->select('list_of_items.id', 'list_of_items.item_name')
            ->where('branch_id', '=', $request->branch_id)
            ->where('category_item_id', '=', $request->category_item_id)
            ->get();

        if (is_null($list_of_items)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        return response()->json($list_of_items, 200);
    }

}
