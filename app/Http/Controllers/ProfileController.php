<?php

namespace App\Http\Controllers;

use App\Models\User;
use DB;
use File;
use Illuminate\Http\Request;
use Response;
use Validator;

class ProfileController extends Controller
{
    public function upload_photo_profile(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'file' => 'required|mimes:png,jpg|max:2048',
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        if ($files = $request->file('file')) {

            //store file into document folder
            $file = $request->file->store('public/documents');

            //store your file into database
            // $document = new User();
            // $document->image_profile = $file;
            // $document->id = $request->user_id;
            // $document->save();
            $user = User::find($request->user_id);

            if (is_null($user)) {
                return response()->json([
                    'message' => 'The data was invalid.',
                    'errors' => ['Data tidak ditemukan!'],
                ], 404);
            }

            $user->image_profile = $file;
            $user->updated_at = \Carbon\Carbon::now();
            $user->save();

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $file,
            ]);
        }
    }

    public function update_data_user(Request $request)
    {
        if ($request->user()->id) {

          info($request);

            $validator = Validator::make($request->all(), [
                'username' => 'required|string|min:3|max:20',
                'tempat_lahir' => 'required|string|min:3',
                'tanggal_lahir' => 'required|date',
                'email' => 'required|string|max:50',
                'nomor_ponsel' => 'required|numeric|digits_between:10,13',
                'alamat' => 'required|string|max:50',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();

                return response()->json([
                    'message' => 'Data yang dimasukkan tidak valid!',
                    'errors' => $errors,
                ], 422);
            }

            $user = User::find($request->user()->id);

            $user->username = $request->username;
            $user->birth_place = $request->tempat_lahir;
            $user->birthdate = $request->tanggal_lahir;
            $user->email = $request->email;
            $user->phone_number = $request->nomor_ponsel;
            $user->address = $request->alamat;
            $user->updated_at = \Carbon\Carbon::now();
            $user->save();

            return response()->json([
                'message' => 'Berhasil mengubah Data',
            ], 200);

        } else {

            return response()->json([
                'message' => 'The user was invalid.',
                'errors' => ['User tidak valid!'],
            ], 422);
        }
    }

    public function get_data_user(Request $request)
    {
        if ($request->user()->id) {

            $user = DB::table('users')
                ->select('users.username', 'users.birth_place', 'users.birthdate', 'users.email',
                    'users.phone_number', 'users.address', 'users.image_profile')
                ->where('users.id', '=', $request->user()->id)
                ->get();

            return response()->json($user, 200);

        } else {

            return response()->json([
                'message' => 'The user was invalid.',
                'errors' => ['User tidak valid!'],
            ], 422);
        }

    }
}
