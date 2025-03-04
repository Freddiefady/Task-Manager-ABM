<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:8|max:255',
        ]);
        try{
            DB::beginTransaction();
            $user = User::create([
                'name'=>$request->post('name'),
                'email'=>$request->post('email'),
                'password'=>$request->post('password'),
            ]);

            $token = $user->createToken($request->name)->plainTextToken;

            DB::commit();
            return response()->json([
                'message' => 'User registered successfully',
                'token' => $token,
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            Log::error('Error Message From Registration Proccess : '. $e->getMessage());
            return response()->json([
                'message' => 'User registration failed',
            ], 500);
        }
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' =>'required|email|max:50',
            'password' =>'required|min:8|max:255',
        ]);

        $user = User::whereEmail($request->email)->first();
        if($user->email && Hash::check($request->password, $user->password))
        {
            $token = $user->createToken($request->email, [], now()->addMinutes(60))->plainTextToken;
            return response()->json([
                'message' => 'User logged in successfully',
                'token' => $token
            ],200);
        }
        return response()->json([
            'message' => 'Credentials doesn\'t match, Try again',
        ], 401);
    }
    public function logout(Request $request)
    {
        $request->user('sanctum')->currentAccessToken()->delete();
        return response()->json([
            'message' => 'User logged out successfully',
        ], 200);
    }
}
