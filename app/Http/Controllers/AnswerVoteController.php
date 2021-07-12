<?php

namespace App\Http\Controllers;

use App\Models\AnswerVote;
use Illuminate\Http\Request;

class AnswerVoteController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'vote' => 'required',
            'update' => 'required'
        ]);
        $validatedData['user_id'] = auth()->id();
        $validatedData['answer_id'] = $validatedData['id'];
        unset($validatedData['id']);
        $mode = $validatedData['update'];
        unset($validatedData['update']);
        if ($mode) {
            $returnable = (object)AnswerVote::create($validatedData);
            return response(json_encode(['message' => "'Vote added ' $returnable"]), 200);
        } else {
            $returnable = AnswerVote::where($validatedData)->delete();
            return response(json_encode(['message' => "'No of votes removed ' $returnable"]), 200);
        }
    }
}
