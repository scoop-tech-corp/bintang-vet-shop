<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use DB;
use Illuminate\Http\Request;
use Validator;

class PasienController extends Controller
{
    public function index(Request $request)
    {

        $patient = DB::table('patients')
            ->join('branches', 'patients.branch_id', '=', 'branches.id')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->select('patients.id', 'patients.branch_id', 'patients.id_member', 'patients.pet_category', 'patients.pet_name', 'patients.pet_gender'
                , 'patients.pet_year_age', 'branches.branch_name', 'users.fullname as created_by',
                DB::raw("DATE_FORMAT(patients.created_at, '%d %b %Y') as created_at"))
            ->where('patients.isDeleted', '=', 'false');

        if ($request->keyword) {
            $patient = $patient->where('patients.id_member', 'like', '%' . $request->keyword . '%')
                ->orwhere('patients.pet_category', 'like', '%' . $request->keyword . '%')
                ->orwhere('patients.pet_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('patients.pet_gender', 'like', '%' . $request->keyword . '%')
                ->orwhere('patients.pet_year_age', 'like', '%' . $request->keyword . '%')
                ->orwhere('branches.branch_name', 'like', '%' . $request->keyword . '%')
                ->orwhere('users.fullname', 'like', '%' . $request->keyword . '%');
        }

        if ($request->orderby) {

            $patient = $patient->orderBy($request->column, $request->orderby);
        }

        $patient = $patient->orderBy('id', 'asc');

        $patient = $patient->get();

        return response()->json($patient, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_hewan' => 'required|min:3|max:51',
            'nama_hewan' => 'required|min:3|max:51',
            'jenis_kelamin_hewan' => 'required|string|max:51',
            'usia_tahun_hewan' => 'required|numeric|min:0',
            'usia_bulan_hewan' => 'required|numeric|min:0|max:12',
            'nama_pemilik' => 'required|string|max:51',
            'alamat_pemilik' => 'required|string|max:101',
            'nomor_ponsel_pengirim' => 'required|numeric|digits_between:10,12',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Pasien yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $lastpatient = DB::table('patients')
            ->where('branch_id', '=', $request->user()->branch_id)
            ->count();

        $getbranchuser = DB::table('branches')
            ->select('branch_code')
            ->where('id', '=', $request->user()->branch_id)
            ->first();

        $patient_number = 'BVC-P-' . $getbranchuser->branch_code . '-' . str_pad($lastpatient + 1, 4, 0, STR_PAD_LEFT);

        $patient = Patient::create([
            'id_member' => $patient_number,
            'pet_category' => $request->kategori_hewan,
            'pet_name' => $request->nama_hewan,
            'pet_gender' => $request->jenis_kelamin_hewan,
            'pet_year_age' => $request->usia_tahun_hewan,
            'pet_month_age' => $request->usia_bulan_hewan,
            'owner_name' => $request->nama_pemilik,
            'owner_address' => $request->alamat_pemilik,
            'owner_phone_number' => strval($request->nomor_ponsel_pengirim),
            'branch_id' => $request->user()->branch_id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(
            [
                'message' => 'Tambah Pasien Berhasil!',
            ], 200
        );
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_hewan' => 'required|min:3|max:51',
            'nama_hewan' => 'required|min:3|max:51',
            'jenis_kelamin_hewan' => 'required|string|max:51',
            'usia_tahun_hewan' => 'required|numeric|min:0',
            'usia_bulan_hewan' => 'required|numeric|min:0|max:12',
            'nama_pemilik' => 'required|string|max:51',
            'alamat_pemilik' => 'required|string|max:101',
            'nomor_ponsel_pengirim' => 'required|numeric|digits_between:10,12',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'Pasien yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $patient = Patient::find($request->id);

        if (is_null($patient)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $patient->pet_category = $request->kategori_hewan;
        $patient->pet_name = $request->nama_hewan;
        $patient->pet_gender = $request->jenis_kelamin_hewan;
        $patient->pet_year_age = $request->usia_tahun_hewan;
        $patient->pet_month_age = $request->usia_bulan_hewan;
        $patient->owner_name = $request->nama_pemilik;
        $patient->owner_address = $request->alamat_pemilik;
        $patient->owner_phone_number = $request->nomor_ponsel_pengirim;
        $patient->user_update_id = $request->user()->id;
        $patient->updated_at = \Carbon\Carbon::now();
        $patient->save();

        return response()->json([
            'message' => 'Berhasil mengupdate Pasien',
        ], 200);
    }

    public function delete(Request $request)
    {
        $patient = Patient::find($request->id);

        if (is_null($patient)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $patient->isDeleted = true;
        $patient->deleted_by = $request->user()->fullname;
        $patient->deleted_at = \Carbon\Carbon::now();
        $patient->save();

        $patient->delete();

        return response()->json([
            'message' => 'Berhasil menghapus Pasien',
        ], 200);
    }
}
