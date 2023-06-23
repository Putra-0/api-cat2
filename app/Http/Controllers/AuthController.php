<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request...
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:8',
            'role_id' => 'exists:roles,id',
            'alamat' => 'required',
            'no_telp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        // Create a new user instance...
        if (User::create(array_merge($validator->validated(), ['password' => bcrypt($request->password)]))) {
            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, please try again later',
            ], 500);
        }

    }

    public function login(Request $request)
    {
        // Validate the request...
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users|max:255',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Attempt to log the user in...
        if (!Auth::attempt($validator->validated())) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User logged in successfully',
            'token' => $token,
            'user' => $user,
        ], 201);

    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request...
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully',
        ], 201);
    }
}
