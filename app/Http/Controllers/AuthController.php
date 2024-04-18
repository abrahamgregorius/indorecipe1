<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6|confirmed'
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $uuid = uuid_create();

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password,
            'role' => 'User',
            'token' => $uuid
        ]);

        Auth::login($user);

        return response()->json([
            'message' => "Register success",
            'accessToken' => $uuid,
        ]);
    }

    public function login(Request $request) {
        if(!Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ])) {
            return response()->json([
                'message' => "Username or password incorrect"
            ], 401);
        }

        $uuid = uuid_create();

        auth()->user->update([
            'token' => $uuid
        ]);

        return response()->json([
            'message' => 'Login success',
            'role' => auth()->user->role,
            'accessToken' => $uuid
        ]);
    }

    public function logout(Request $request) {
        $user = User::where('token', $request->bearerToken())->first();

        $user->update([
            'token' =>null
        ]);

        return response()->json([
            'message' => 'Logout success'
        ]);

    }
}
