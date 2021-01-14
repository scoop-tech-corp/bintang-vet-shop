<?php

namespace App\Http\Controllers;

use App\Models\InPatient;
use App\Models\InPatientAdmissions;
use DB;
use Illuminate\Http\Request;
use Validator;

class DokterRawatInapController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $data = DB::table('in_patients')
            ->join('users', 'in_patients.user_id', '=', 'users.id')
            ->join('users as user_doctor', 'in_patients.doctor_user_id', '=', 'user_doctor.id')
            ->join('patients', 'in_patients.patient_id', '=', 'patients.id')
            ->join('branches', 'patients.branch_id', '=', 'branches.id')
            ->select('in_patients.id as id', 'in_patients.id_number', 'in_patients.patient_id',
                'patients.id_member as id_number_patient', 'patients.pet_category', 'patients.pet_name', 'patients.pet_gender',
                'patients.pet_year_age', 'patients.pet_month_age', 'patients.owner_name', 'patients.owner_address',
                'patients.owner_phone_number', 'in_patients.complaint', 'in_patients.registrant',
                'in_patients.estimate_day', 'in_patients.reality_day', 'user_doctor.id as user_doctor_id', 'in_patients.acceptance_status',
                'user_doctor.username as username_doctor', 'users.fullname as created_by',
                DB::raw("DATE_FORMAT(in_patients.created_at, '%d %b %Y') as created_at"), 'users.branch_id as user_branch_id');

        if ($request->user()->role == 'dokter') {
            $data = $data->where('users.branch_id', '=', $request->user()->branch_id);
        }

        if ($request->keyword) {

            $data = $data->where('in_patients.id', 'like', '%' . $request->keyword . '%')
                ->orwhere('patients.id_member', 'like', '%' . $request->keyword . '%')
                ->orwhere('patients.pet_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%')
                ->orwhere('in_patients.complaint', 'like', '%' . $request->keyword . '%')
                ->orwhere('in_patients.registrant', 'like', '%' . $request->keyword . '%')
                ->orwhere('user_doctor.username', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%');
        }

        if ($request->orderby) {

            $data = $data->orderBy($request->column, $request->orderby);
        }

        $data = $data->orderBy('in_patients.id', 'asc');

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

        $in_patient = InPatient::find($request->id);

        if (is_null($in_patient)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        } elseif ($in_patient->acceptance_status == 1) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak dapat diubah karena sudah diterima oleh dokter!'],
            ], 422);
        } elseif ($in_patient->acceptance_status == 2) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak dapat diubah karena sudah ditolak oleh dokter!'],
            ], 422);
        }

        $in_patient->acceptance_status = 1;
        $in_patient->user_update_id = $request->user()->id;
        $in_patient->updated_at = \Carbon\Carbon::now();
        $in_patient->save();

        $patient = InPatientAdmissions::create([
            'inpatient_id' => $in_patient->id,
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

        $in_patient = InPatient::find($request->id);

        if (is_null($in_patient)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        } elseif ($in_patient->acceptance_status == 1) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak dapat diubah karena sudah diterima oleh dokter!'],
            ], 422);
        } elseif ($in_patient->acceptance_status == 2) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak dapat diubah karena sudah ditolak oleh dokter!'],
            ], 422);
        }

        $in_patient->acceptance_status = 2;
        $in_patient->user_update_id = $request->user()->id;
        $in_patient->updated_at = \Carbon\Carbon::now();
        $in_patient->save();

        $validator = Validator::make($request->all(), [
            'alasan' => 'required|string|min:10|max:100',
        ]);

        $in_patient = InPatientAdmissions::create([
            'inpatient_id' => $in_patient->id,
            'reason' => $request->alasan,
            'acceptance_status' => 1,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Proses Data Berhasil',
        ], 200);

    }
}
