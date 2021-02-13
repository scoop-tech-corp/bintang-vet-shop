<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function DropDownPatient(Request $request)
    {
        if ($request->user()->role == 'dokter') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $data = DB::table('check_up_results')
            ->join('registrations', 'check_up_results.patient_registration_id', '=', 'registrations.id')
            ->join('users', 'registrations.user_id', '=', 'users.id')
            ->join('users as user_doctor', 'registrations.doctor_user_id', '=', 'user_doctor.id')
            ->join('branches', 'user_doctor.branch_id', '=', 'branches.id')
            ->join('patients', 'registrations.patient_id', '=', 'patients.id')
            ->select('check_up_results.id', 'registrations.id_number as registration_number', 'patients.pet_name');
        

        if ($request->user()->role == 'resepsionis') {
            $data = $data->where('user_doctor.branch_id', '=', $request->user()->branch_id);
        }

        $data = $data->get();

        return response()->json($data, 200);
    }

    public function index(Request $request)
    {
        if ($request->user()->role == 'dokter') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $data = DB::table('check_up_results');
    }

    public function create(Request $request)
    {
        if ($request->user()->role == 'dokter') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

    }

    public function update(Request $request)
    {
        if ($request->user()->role == 'dokter') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

    }

    public function delete(Request $request)
    {
        if ($request->user()->role == 'dokter') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

    }
}
