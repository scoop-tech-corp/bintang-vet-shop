<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CageController extends Controller
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
              'list_of_items.selling_price',
              'list_of_items.capital_price',
              'list_of_items.profit',
              'list_of_items.image',
              'branches.id as branch_id',
              'branches.branch_name',
              'users.id as user_id',
              'users.fullname as created_by',
              DB::raw("DATE_FORMAT(list_of_items.created_at, '%d %b %Y') as created_at"))
          ->where('list_of_items.isDeleted', '=', 0)
          ->where('list_of_items.category', '=', 'cage');

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
}
