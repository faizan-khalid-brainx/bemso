<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            return response(json_encode(['message' => 'Invalid Login Credentials']), 401);
        }
        $user->tokens()->delete();
        auth()->login($user);
        return response(json_encode(['message' => 'Login Successful',
            'token' => $user->createToken($request->email)->plainTextToken]), 200);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response(json_encode(['message' => "$request->bearerToken() logged out"]), 200);
    }

    public function checkUser(Request $request)
    {
        $tokens = auth()->user()->tokens();
        foreach ($tokens as $token) {
            if (Hash::check($request->bearerToken(), $token->planetext())) {
                return response(json_encode(['message' => 'User Validated'], 200));
            }
        }
        return response(json_encode(['message' => 'Invalid User']), 401);
    }
}
