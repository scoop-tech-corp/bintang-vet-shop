<?php

namespace App\Http\Controllers;

use App\Exports\RecapPayment;
use App\Models\Branch;
use App\Models\ListofItems;
use App\Models\Master_Payments;
use App\Models\Payment;
use DB;
use Illuminate\Http\Request;
use PDF;
use Validator;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payment = DB::table('payments as py')
            ->join('master_payments as mp', 'py.master_payment_id', '=', 'mp.id')
            ->join('list_of_items as loi', 'py.list_of_item_id', '=', 'loi.id')
            ->join('users', 'loi.user_id', '=', 'users.id')
            ->join('branches', 'loi.branch_id', '=', 'branches.id');

        $payment = $payment->select(
            'py.id',
            'mp.payment_number',
            'loi.item_name',
            'py.total_item',
            'loi.category',
            DB::raw("TRIM(loi.selling_price)+0 as each_price"),
            DB::raw("TRIM(loi.selling_price * py.total_item)+0 as overall_price"),
            'branches.id as branch_id',
            'branches.branch_name',
            'users.id as user_id',
            'users.fullname as created_by',
            DB::raw("DATE_FORMAT(py.created_at, '%d/%m/%Y') as created_at"))
            ->where('py.isDeleted', '=', 0);

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
        $result_items = json_decode($items, true);
        //$items;
        //json_decode($items, true);

        $lastnumber = DB::table('master_payments')
            ->where('branch_id', '=', $request->branch_id)
            ->count();

        $branch = Branch::find($request->branch_id);

        $payment_number = 'EVS-P-' . $branch->branch_code . '-' . str_pad($lastnumber + 1, 4, 0, STR_PAD_LEFT);

        $master_payment = Master_Payments::create([
            'payment_number' => $payment_number,
            'user_id' => $request->user()->id,
            'branch_id' => $request->branch_id,
        ]);

        foreach ($result_items as $value) {

            $find_item = ListofItems::find($value['list_of_item_id']);

            if (is_null($find_item)) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Barang tidak ada!'],
                ], 422);
            }

            $res_value = $find_item->total_item - $value['total_item'];

            if ($res_value < 0) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Stok Barang ' . $find_item->item_name . ' kurang atau habis!'],
                ], 422);
            }

            $find_item->total_item = $res_value;
            $find_item->user_update_id = $request->user()->id;
            $find_item->updated_at = \Carbon\Carbon::now();
            $find_item->save();

            $payment = Payment::create([
                'list_of_item_id' => $value['list_of_item_id'],
                'total_item' => $value['total_item'],
                'master_payment_id' => $master_payment->id,
                'user_id' => $request->user()->id,
            ]);

        }

        return response()->json(
            [
                'message' => 'Tambah Data Berhasil!',
                'master_payment_id' => $master_payment->id,
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

        $find_item = ListofItems::find($payment->list_of_item_id);

        $res_value = $find_item->total_item + $payment->total_item;

        $find_item->total_item = $res_value;
        $find_item->user_update_id = $request->user()->id;
        $find_item->updated_at = \Carbon\Carbon::now();
        $find_item->save();

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

        $item = DB::table('list_of_items')
            ->select('id', 'item_name', 'category', DB::raw("TRIM(selling_price)+0 as selling_price"))
            ->where('isDeleted', '=', 0)
            ->where('branch_id', '=', $request->branch_id)
            ->get();

        return response()->json($item, 200);
    }
    public function print_receipt(Request $request)
    {
        $res_list_of_payments = "";
        $payments = $request->list_of_payments;
        $result_payments = json_decode($payments, true);
        //$payments;
        //json_decode($payments, true);

        $data_header = DB::table('master_payments as mp')
            ->join('users', 'mp.user_id', '=', 'users.id')
            ->join('branches', 'mp.branch_id', '=', 'branches.id')
            ->select(
                'branches.branch_name',
                'branches.address',
                'mp.payment_number',
                'users.fullname as cashier_name',
                DB::raw("DATE_FORMAT(mp.created_at, '%d %b %Y %H:%i:%s') as paid_time"))
            ->where('mp.id', '=', $request->master_payment_id)
            ->get();

        $data_detail = DB::table('payments as py')
            ->join('list_of_items as loi', 'py.list_of_item_id', '=', 'loi.id')
            ->select(
                'loi.item_name',
                'py.total_item',
                DB::raw("TRIM(loi.selling_price)+0 as each_price"),
                DB::raw("TRIM(py.total_item * loi.selling_price)+0 as total_price"))
            ->where('py.master_payment_id', '=', $request->master_payment_id)
            ->get();

        $price_overall = DB::table('payments as py')
            ->join('list_of_items as loi', 'py.list_of_item_id', '=', 'loi.id')
            ->select(
                DB::raw("TRIM(SUM(py.total_item * loi.selling_price))+0 as price_overall"))
            ->where('py.master_payment_id', '=', $request->master_payment_id)
            ->first();

        $data = [
            'data_header' => $data_header,
            'data_detail' => $data_detail,
            'price_overall' => $price_overall,
        ];

        $find_payment_number = DB::table('master_payments')
            ->select('payment_number')
            ->where('id', '=', $request->master_payment_id)
            ->first();

        $pdf = PDF::loadview('pdf', $data);

        return $pdf->download($find_payment_number->payment_number . '.pdf');
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
