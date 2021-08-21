<?php

namespace App\Http\Controllers;

use App\Exports\RecapPayment;
use App\Models\ListofItems;
use App\Models\Payment;
use DB;
use Illuminate\Http\Request;
use Validator;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payment = DB::table('payments as py')
            ->join('list_of_items as loi', 'py.list_of_item_id', '=', 'loi.id')
            ->join('users', 'loi.user_id', '=', 'users.id')
            ->join('branches', 'loi.branch_id', '=', 'branches.id');

        $payment = $payment->select(
            'py.id',
            'loi.item_name',
            'py.total_item',
            'loi.category',
            DB::raw("TRIM(loi.selling_price)+0 as each_price"),
            DB::raw("TRIM(loi.selling_price * py.total_item)+0 as overall_price"),
            'branches.id as branch_id',
            'branches.branch_name',
            'users.id as user_id',
            'users.fullname as created_by',
            DB::raw("DATE_FORMAT(py.created_at, '%d %b %Y') as created_at"));

        $payment = $payment->where('py.isDeleted', '=', 0);

        if ($request->branch_id && $request->user()->role == 'admin') {
            $payment = $payment->where('loi.branch_id', '=', $request->branch_id);
        }

        if ($request->user()->role == 'kasir') {
            $payment = $payment->where('loi.branch_id', '=', $request->user()->branch_id);
        }

        if ($request->keyword) {

            $payment = $payment->where('loi.item_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('branches.branch_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%');
        }

        if ($request->orderby) {
            $payment = $payment->orderBy($request->column, $request->orderby);
        }

        $payment = $payment->orderBy('py.id', 'desc');

        $payment = $payment->get();

        return response()->json($payment, 200);
    }
    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'list_of_items.*.list_of_item_id' => 'required|numeric',
            'list_of_items.*.total_item' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Data yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $items = $request->list_of_items;
        $result_items = json_decode(json_encode($items), true);

        foreach ($result_items as $value) {

            $find_item = ListofItems::find($value['list_of_item_id']);

            if (is_null($find_item)) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Barang tidak ada!'],
                ], 422);
            }

            $payment = Payment::create([
                'list_of_item_id' => $value['list_of_item_id'],
                'total_item' => $value['total_item'],
                'user_id' => $request->user()->id,
            ]);

        }

        return response()->json(
            [
                'message' => 'Tambah Data Berhasil!',
            ], 200
        );

    }
    public function delete(Request $request)
    {
        if ($request->user()->role == 'kasir') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $payment = Payment::where('id', '=', $request->id)
            ->count();

        if ($payment == 0) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak ada ada!'],
            ], 422);
        }

        $payment = Payment::find($request->id);

        $payment->user_update_id = $request->user()->id;
        $payment->isDeleted = 1;
        $payment->deleted_by = $request->user()->id;
        $payment->updated_at = \Carbon\Carbon::now();
        $payment->deleted_at = \Carbon\Carbon::now();
        $payment->save();

        return response()->json([
            'message' => 'Berhasil menghapus Data',
        ], 200);
    }

    public function filter_item(Request $request)
    {
        if ($request->user()->role == 'kasir') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $item = DB::table('list_of_items')
            ->select('item_name', 'category')
            ->where('isDeleted', '=', 0)
            ->where('branch_id', '=', $request->branch_id)
            ->get();

        return response()->json($item, 200);
    }
    public function print_receipt(Request $request)
    {
      $payments = $request->list_of_payments;
      $result_payments = json_decode($payments, true);
      //$payments;
        //json_decode($payments, true);


    }
    public function download_report_excel(Request $request)
    {
        $date = "";
        $date = \Carbon\Carbon::now()->format('d-m-y');

        $filename = 'Rekap Pembayaran ' . $date . '.xlsx';

        $branch_id = "";

        if ($request->user()->role == 'kasir') {
            $branch_id = $request->user()->branch_id;
        } else {
            $branch_id = $request->branch_id;
        }

        return (new RecapPayment($request->orderby, $request->column, $request->keyword, $branch_id))
            ->download($filename);
    }
}
