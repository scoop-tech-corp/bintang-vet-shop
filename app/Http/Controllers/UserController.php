<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        // $credentials = $request->only('email', 'password');

        // try {
        //     if (!$token = JWTAuth::attempt($credentials)) {
        //         return response()->json(['error' => 'invalid_credentials'], 400);
        //     }
        // } catch (JWTException $e) {
        //     return response()->json(['error' => 'could_not_create_token'], 500);
        // }

        // return response()->json(compact('token'));

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->messages();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 401);
        }

        $credentials = $request->only('username', 'password');
        $token = null;

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => ['The password or username you entered is wrong!'],
                ], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json([
                'message' => 'Could not create token!',
            ], 500);
            //return response()->json(['error' => 'could_not_create_token'], 500);
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
        $messages = [
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, simbol, dan angka!'
        ];

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:25|unique:users',
            'fullname' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ], $messages);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors()->toJson(), 400);
        // }
        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 400);
        }

        $user = User::create([
            'username' => $request->json('username'),
            'fullname' => $request->json('fullname'),
            'email' => $request->json('email'),
            'password' => bcrypt($request->json('password')),
            // 'name' => $request->get('name'),
            // 'email' => $request->get('email'),
            // 'password' => Hash::make($request->get('password')),
        ]);

        // $token = JWTAuth::fromUser($user);

        // return response()->json(compact('user', 'token'), 201);

        return response()->json(
            [
                'message' => 'Register Success!',
            ]
        );
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
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 401);
        }

        $credentials = $request->only('email');
        $token = null;

        $token = JWTAuth::invalidate($credentials);

        return response()->json(
            [
                'message' => 'Success!',
            ]
        );

    }
}
