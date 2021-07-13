<?php

namespace App\Http\Controllers;

use App\Models\QuestionVote;
use Illuminate\Http\Request;

class QuestionVoteController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'vote' => 'required',
            'update' => 'required'
        ]);
        $validatedData['user_id'] = auth()->id();
        $validatedData['question_id'] = $validatedData['id'];
        unset($validatedData['id']);
        $mode = $validatedData['update'];
        unset($validatedData['update']);
        if ($mode) {
            $returnable = (object)QuestionVote::create($validatedData);
            return response()->json(['message' => "'Vote added' $returnable"], 200);
        } else {
            $returnable = QuestionVote::where($validatedData)->delete();
            return response()->json(['message' => "'No of votes removed' $returnable"], 200);
        }
    }
}
