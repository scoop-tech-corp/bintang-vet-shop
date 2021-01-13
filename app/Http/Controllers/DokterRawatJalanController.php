<?php

namespace App\Http\Controllers;

use App\Models\OutPatient;
use App\Models\OutPatientAdmissions;
use DB;
use Illuminate\Http\Request;
use Validator;

class DokterRawatJalanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $data = DB::table('out_patients')
            ->join('users', 'out_patients.user_id', '=', 'users.id')
            ->join('users as user_doctor', 'out_patients.doctor_user_id', '=', 'user_doctor.id')
            ->join('patients', 'out_patients.patient_id', '=', 'patients.id')
            ->join('branches', 'patients.branch_id', '=', 'branches.id')
            ->select('out_patients.id as id', 'out_patients.id_number', 'out_patients.patient_id',
                'patients.id_member as id_number_patient', 'patients.pet_category', 'patients.pet_name', 'patients.pet_gender',
                'patients.pet_year_age', 'patients.pet_month_age', 'patients.owner_name', 'patients.owner_address',
                'patients.owner_phone_number', 'complaint', 'registrant', 'user_doctor.id as user_doctor_id',
                'user_doctor.username as username_doctor', 'users.fullname as created_by', 'out_patients.acceptance_status',
                DB::raw("DATE_FORMAT(out_patients.created_at, '%d %b %Y') as created_at"), 'users.branch_id as user_branch_id')
            ->where('out_patients.acceptance_status', '=', '0');

        if ($request->user()->role == 'dokter') {
            $data = $data->where('users.branch_id', '=', $request->user()->branch_id);
        }

        if ($request->keyword) {

            $data = $data->where('out_patients.id', 'like', '%' . $request->keyword . '%')
                ->orwhere('patients.id_member', 'like', '%' . $request->keyword . '%')
                ->orwhere('patients.pet_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%')
                ->orwhere('out_patients.complaint', 'like', '%' . $request->keyword . '%')
                ->orwhere('out_patients.registrant', 'like', '%' . $request->keyword . '%')
                ->orwhere('user_doctor.username', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%');
        }

        if ($request->orderby) {

            $data = $data->orderBy($request->column, $request->orderby);
        }

        $data = $data->orderBy('out_patients.id', 'asc');

        $data = $data->get();

        return response()->json($data, 200);
    }

    public function accept(Request $request)
    {
        if ($request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $out_patient = OutPatient::find($request->id);

        if (is_null($out_patient)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        } elseif ($out_patient->acceptance_status == 1) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak dapat diubah karena sudah diterima oleh dokter!'],
            ], 422);
        } elseif ($out_patient->acceptance_status == 2) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak dapat diubah karena sudah ditolak oleh dokter!'],
            ], 422);
        }

        $out_patient->acceptance_status = 1;
        $out_patient->user_update_id = $request->user()->id;
        $out_patient->updated_at = \Carbon\Carbon::now();
        $out_patient->save();

        $patient = OutPatientAdmissions::create([
            'outpatient_id' => $out_patient->id,
            'reason' => '',
            'acceptance_status' => 1,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Proses Data Berhasil',
        ], 200);
    }

    public function decline(Request $request)
    {
        if ($request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $out_patient = OutPatient::find($request->id);

        if (is_null($out_patient)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        } elseif ($out_patient->acceptance_status == 1) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak dapat diubah karena sudah diterima oleh dokter!'],
            ], 422);
        } elseif ($out_patient->acceptance_status == 2) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak dapat diubah karena sudah ditolak oleh dokter!'],
            ], 422);
        }

        $out_patient->acceptance_status = 2;
        $out_patient->user_update_id = $request->user()->id;
        $out_patient->updated_at = \Carbon\Carbon::now();
        $out_patient->save();

        $patient = OutPatientAdmissions::create([
            'outpatient_id' => $out_patient->id,
            'reason' => $request->reason,
            'acceptance_status' => 1,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Proses Data Berhasil',
        ], 200);
    }
}
