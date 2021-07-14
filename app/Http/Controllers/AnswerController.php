<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'question_id' => 'required',
            'content' => 'required',
        ]);
        $validatedData['user_id'] = auth()->id();
        $answer = Answer::create($validatedData);
        response()->json(['message' => "'Answer published at' $answer->created_at"], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'content' => 'required'
        ]);
        Answer::where('id',$request->id)->update(['content' => $request->content]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        Answer::where('id',$request->id)->delete();
    }

}
