<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id','<>',auth()->id())->get(['id','name']);
        return response()->json(['users' => $users],200);
    }
}
