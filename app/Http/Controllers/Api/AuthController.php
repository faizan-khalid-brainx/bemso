<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid Login Credentials'], 401);
        }
//        $user->tokens()->delete();
        auth()->login($user);
        return response()->json(['message' => 'Login Successful',
            'token' => $user->createToken($request->email)->plainTextToken], 200);
    }

    public function logout(Request $request)
    {
        if (auth()->check()){
            auth()->user()->tokens()->delete();
            return response()->json(['message' => "{$request->bearerToken()} logged out"], 200);
        }else{
            return response()->json(['message' => "Unauthorized"], 401);
        }
    }

    public function checkUser(Request $request)
    {
        return response()->json(['message' => auth()->id()], 200);
    }
}
