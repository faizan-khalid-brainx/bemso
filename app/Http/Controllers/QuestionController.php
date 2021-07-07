<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{

    public function display()
    {
        //question, intermediary_table, users
        $question = Question::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $validatedData['user_id'] = auth()->id();
        $que = Question::create($validatedData);
        return response(json_encode(['message' => "'Question published at' $que->created_at"]),200);
    }
}
