<?php

namespace App\Http\Controllers;

use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->messages();

            return response()->json([
                'message' => 'User yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 401);
        }

        $credentials = $request->only('username', 'password');
        $token = null;

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => 'User yang dimasukkan tidak valid!',
                    'errors' => ['Username atau password yang dimasukkan salah!'],
                ], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json([
                'message' => 'Tidak dapat membuat token!',
            ], 500);
            //return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user = User::find($request->user()->id);

        if ($user->status == 'Non Aktif') {
            return response()->json([
                'message' => 'Akun yang anda gunakan tidak aktif!',
                'errors' => ['Akses tidak diijinkan!'],
            ], 403);
        }

        return response()->json(
            [
                'user_id' => $request->user()->id,
                'token' => $token,
                'username' => $user->username,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'role' => $user->role,
            ]
        );
    }

    public function register(Request $request)
    {

        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'User yang dimasukkan tidak valid!',
                'errors' => ['Akses tidak diijinkan!'],
            ], 403);
        }

        $messages = [
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, simbol, dan angka!',
        ];

        $validator = Validator::make($request->all(), [
            'username' => 'required|min:3|max:25|unique:users,username',
            'nama_lengkap' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'nomor_ponsel' => 'required|numeric|digits_between:10,12|unique:users,phone_number',
            'role' => 'required|string',
            'status' => 'required|boolean',
            'kode_cabang' => 'required|string',
            'id_cabang' => 'required|integer',
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'User yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 422);
        }

        $lastuser = DB::table('users')
            ->where('branch_id', '=', $request->id_cabang)
            ->count();

        $staff_number = 'BVC-U-' . $request->json('kode_cabang') . '-' . str_pad($lastuser + 1, 4, 0, STR_PAD_LEFT);

        $user = User::create([
            'staffing_number' => $staff_number,
            'username' => $request->json('username'),
            'fullname' => $request->json('nama_lengkap'),
            'email' => $request->json('email'),
            'password' => bcrypt($request->json('password')),
            'status' => $request->json('status'),
            'phone_number' => strval($request->json('nomor_ponsel')),
            'role' => $request->json('role'),
            'branch_id' => $request->json('id_cabang'),
            'created_by' => $request->user()->fullname,
        ]);

        return response()->json(
            [
                'message' => 'Register Berhasil!',
            ], 200
        );
    }

    public function index(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Access is not allowed!'],
            ], 403);
        }

        if ($request->orderby == 'asc') {

            $user = DB::table('users')
                ->join('branches', 'users.branch_id', '=', 'branches.id')
                ->select('users.id', 'users.staffing_number', 'users.username', 'users.fullname', 'users.email'
                    , 'users.role', 'users.phone_number', 'branches.branch_name', 'users.status', 'users.created_by', 'users.created_at')->orderBy($request->column, 'asc')->get();

        } else if ($request->orderby == 'desc') {

            $user = DB::table('users')
                ->join('branches', 'users.branch_id', '=', 'branches.id')
                ->select('users.id', 'users.staffing_number', 'users.username', 'users.fullname', 'users.email'
                    , 'users.role', 'users.phone_number', 'branches.branch_name', 'users.status', 'users.created_by', 'users.created_at')->orderBy($request->column, 'desc')->get();

        } else {

            $user = DB::table('users')
                ->join('branches', 'users.branch_id', '=', 'branches.id')
                ->select('users.id', 'users.staffing_number', 'users.username', 'users.fullname', 'users.email'
                    , 'users.role', 'users.phone_number', 'branches.branch_name', 'users.status', 'users.created_by', 'users.created_at')->get();

        }

        return response()->json($user, 200);
    }

    public function update(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'User yang dimasukkan tidak valid!',
                'errors' => ['Akses tidak diijinkan!'],
            ], 403);
        }

        $messages = [
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, simbol, dan angka!',
        ];

        $validator = Validator::make($request->all(), [
            'nomor_kepegawaian' => 'required|string',
            'nama_lengkap' => 'required|min:3|max:255',
            //'password' => 'required|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'role' => 'required|string',
            'status' => 'required|boolean',
            'id_cabang' => 'required|integer',
            'kode_cabang' => 'required|string',
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        $user = User::find($request->id);

        if (is_null($user)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data not found!'],
            ], 404);
        }

        $chk_user_branch = DB::table('users')
            ->select('branch_id')
            ->where('id', '=', $request->id)->get();

        $staff_number = '';

        if ($chk_user_branch != $request->id_cabang) {
            $lastuser = DB::table('users')
                ->where('branch_id', '=', $request->id_cabang)
                ->count();

            $staff_number = 'BVC-U-' . $request->json('kode_cabang') . '-' . str_pad($lastuser + 1, 4, 0, STR_PAD_LEFT);
        } else {

            $staff_number = $request->nomor_kepegawaian;

        }

        $user->staffing_number = $staff_number;
        $user->fullname = $request->nama_lengkap;
        //$user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->status = $request->status;
        $user->branch_id = $request->id_cabang;
        $user->status = $request->status;
        $user->update_by = $request->user()->fullname;
        $user->updated_at = \Carbon\Carbon::now();
        $user->save();

        return response()->json([
            'message' => 'Berhasil mengupdate Cabang',
        ], 200);
    }

    public function search(Type $var = null)
    {
        # code...
    }

    // public function getAuthenticatedUser()
    // {
    //     try {

    //         if (!$user = JWTAuth::parseToken()->authenticate()) {
    //             return response()->json(['user_not_found'], 404);
    //         }

    //     } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

    //         return response()->json(['token_expired'], $e->getStatusCode());

    //     } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

    //         return response()->json(['token_invalid'], $e->getStatusCode());

    //     } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

    //         return response()->json(['token_absent'], $e->getStatusCode());

    //     }

    //     return response()->json(compact('user'));
    // }

    public function logout(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'User yang dimasukkan tidak valid!',
                'errors' => $errors,
            ], 401);
        }

        $credentials = $request->only('username');
        $token = null;

        $token = JWTAuth::invalidate($credentials);

        return response()->json(
            [
                'message' => 'Success!',
            ], 200);

    }
}
