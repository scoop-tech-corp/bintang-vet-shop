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
        if ($request->orderby == 'asc') {

            $patient = DB::table('patients')
                ->join('branches', 'patients.branch_id', '=', 'branches.id')
                ->select('patients.id', 'patients.branch_id', 'patients.id_member', 'patients.pet_category', 'patients.pet_name', 'patients.pet_gender'
                    , 'patients.pet_year_age', 'branches.branch_name', 'patients.created_by',
                    DB::raw("DATE_FORMAT(patients.created_at, '%d %b %Y') as created_at"))->orderBy($request->column, 'asc')
                ->where('patients.isDeleted', '=', 'false')
                ->get();

        } else if ($request->orderby == 'desc') {

            $patient = DB::table('patients')
                ->join('branches', 'patients.branch_id', '=', 'branches.id')
                ->select('patients.id', 'patients.branch_id', 'patients.id_member', 'patients.pet_category', 'patients.pet_name', 'patients.pet_gender'
                    , 'patients.pet_year_age', 'branches.branch_name', 'patients.created_by',
                    DB::raw("DATE_FORMAT(patients.created_at, '%d %b %Y') as created_at"))->orderBy($request->column, 'desc')
                ->where('patients.isDeleted', '=', 'false')
                ->get();

        } else {

            $patient = DB::table('patients')
                ->join('branches', 'patients.branch_id', '=', 'branches.id')
                ->select('patients.id', 'patients.branch_id', 'patients.id_member', 'patients.pet_category', 'patients.pet_name', 'patients.pet_gender'
                    , 'patients.pet_year_age', 'branches.branch_name', 'patients.created_by',
                    DB::raw("DATE_FORMAT(patients.created_at, '%d %b %Y') as created_at"))
                ->where('patients.isDeleted', '=', 'false')
                ->get();

        }

        return response()->json($patient, 200);
    }

    public function detail(Request $request)
    {
        $patient = DB::table('patients')
            ->join('branches', 'patients.branch_id', '=', 'branches.id')
            ->select('patients.id', 'patients.branch_id', 'patients.id_member', 'patients.pet_category', 'patients.pet_name', 'patients.pet_gender'
                , 'patients.pet_year_age', 'patients.pet_month_age', 'branches.branch_name', 'patients.created_by',
                DB::raw("DATE_FORMAT(patients.created_at, '%d %b %Y') as created_at"),
                'patients.owner_name', 'patients.owner_address', 'patients.owner_phone_number')
                ->where('patients.id', '=', $request->id)
                ->get();

        return response()->json($patient, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_hewan' => 'required|min:3|max:25',
            'nama_hewan' => 'required|min:3|max:25',
            'jenis_kelamin_hewan' => 'required|string|max:10',
            'usia_tahun_hewan' => 'required|numeric|min:0',
            'usia_bulan_hewan' => 'required|numeric|min:0|max:12',
            'nama_pemilik' => 'required|string|max:25',
            'alamat_pemilik' => 'required|string|max:25',
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
            'created_by' => $request->user()->fullname,
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
            'kategori_hewan' => 'required|min:3|max:25',
            'nama_hewan' => 'required|min:3|max:25',
            'jenis_kelamin_hewan' => 'required|string|max:10',
            'usia_tahun_hewan' => 'required|numeric|min:0',
            'usia_bulan_hewan' => 'required|numeric|min:0|max:12',
            'nama_pemilik' => 'required|string|max:25',
            'alamat_pemilik' => 'required|string|max:25',
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
        $patient->update_by = $request->user()->fullname;
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

    public function search(Request $request)
    {
        # code...
    }
}
