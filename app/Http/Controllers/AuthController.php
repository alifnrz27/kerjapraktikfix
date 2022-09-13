<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ValidEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email'=> 'required|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required'
        ]);
        
        if ($request->password != $request->password_confirmation){
            return response()->json([
                'message' => 'Password don\'t match',
            ], 400);
        }

        // cek email itera atau bukan
        $checkEmail = explode("@", $request['email']);
        $nameEmail = $checkEmail[1];
        $validateEmail = ValidEmail::where('name', $nameEmail)->first();
        if (!$validateEmail){
            return response()->json([
                'message' => 'Email Unauthorized',
            ], 403); 
        }
        $username = $checkEmail[0];

        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'user_status_id' => 1,
        ]);

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('token-name')->plainTextToken;
        return response()->json([
            'message' => 'success',
            'user' => $user,
            'token' =>$token,
        ], 200); 
    }
    
    public function login(Request $request){
        $request->validate([
            'email'=> 'required',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $token = $user->createToken('token-name')->plainTextToken;
        return response()->json([
            'message' => 'success',
            'user' => $user,
            'token' =>$token,
        ], 200);
    }

    public function logout(Request $request){
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
