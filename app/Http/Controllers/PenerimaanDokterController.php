<?php

namespace App\Http\Controllers;
use App\Models\Registration;
use App\Models\DoctorAcceptance;
use DB;
use Illuminate\Http\Request;
use Validator;

class PenerimaanDokterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        $data = DB::table('registrations')
            ->join('users', 'registrations.user_id', '=', 'users.id')
            ->join('users as user_doctor', 'registrations.doctor_user_id', '=', 'user_doctor.id')
            ->join('patients', 'registrations.patient_id', '=', 'patients.id')
            ->join('branches', 'patients.branch_id', '=', 'branches.id')
            ->select('registrations.id as id', 'registrations.id_number', 'registrations.patient_id',
                'patients.id_member as id_number_patient', 'patients.pet_category', 'patients.pet_name', 'patients.pet_gender',
                'patients.pet_year_age', 'patients.pet_month_age', 'patients.owner_name', 'patients.owner_address',
                'patients.owner_phone_number', 'complaint', 'registrant', 'user_doctor.id as user_doctor_id',
                'user_doctor.username as username_doctor', 'users.fullname as created_by', 'registrations.acceptance_status',
                DB::raw("DATE_FORMAT(registrations.created_at, '%d %b %Y') as created_at"), 'users.branch_id as user_branch_id')
            ->where('registrations.acceptance_status', '=', '0');

        if ($request->user()->role == 'dokter') {
            $data = $data->where('users.branch_id', '=', $request->user()->branch_id);
        }

        if ($request->keyword) {

            $data = $data->where('registrations.id', 'like', '%' . $request->keyword . '%')
                ->orwhere('patients.id_member', 'like', '%' . $request->keyword . '%')
                ->orwhere('patients.pet_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%')
                ->orwhere('registrations.complaint', 'like', '%' . $request->keyword . '%')
                ->orwhere('registrations.registrant', 'like', '%' . $request->keyword . '%')
                ->orwhere('user_doctor.username', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%');
        }

        if ($request->orderby) {

            $data = $data->orderBy($request->column, $request->orderby);
        }

        $data = $data->orderBy('registrations.id', 'asc');

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

        $registration = Registration::find($request->id);

        if (is_null($registration)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        } elseif ($registration->acceptance_status == 1) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak dapat diubah karena sudah diterima oleh dokter!'],
            ], 422);
        } elseif ($registration->acceptance_status == 2) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak dapat diubah karena sudah ditolak oleh dokter!'],
            ], 422);
        }

        $registration->acceptance_status = 1;
        $registration->user_update_id = $request->user()->id;
        $registration->updated_at = \Carbon\Carbon::now();
        $registration->save();

        $patient = DoctorAcceptance::create([
            'patient_registration_id' => $registration->id,
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

        $registration = Registration::find($request->id);

        if (is_null($registration)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        } elseif ($registration->acceptance_status == 1) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak dapat diubah karena sudah diterima oleh dokter!'],
            ], 422);
        } elseif ($registration->acceptance_status == 2) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak dapat diubah karena sudah ditolak oleh dokter!'],
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'alasan' => 'required|string|min:10|max:100',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Data yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $patient = DoctorAcceptance::create([
            'patient_registration_id' => $registration->id,
            'reason' => $request->alasan,
            'acceptance_status' => 2,
            'user_id' => $request->user()->id,
        ]);

        $registration->acceptance_status = 2;
        $registration->user_update_id = $request->user()->id;
        $registration->updated_at = \Carbon\Carbon::now();
        $registration->save();

        return response()->json([
            'message' => 'Proses Data Berhasil',
        ], 200);
    }
}
