<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{

    public function display()
    {
        $questions = Question::with('user:id,email,name')
            ->select(['id', 'title', 'content', 'created_at', 'user_id'])
            ->withCount('answers', 'user_votes')->get();
        $returnable = $questions->map(function ($question) {
            $returnable = $question->getAttributes();
            // removing foreign key
            unset($returnable['user_id']);
            // formatting date
            $returnable['created_at'] = Carbon::parse($question['created_at'])->format('jS M Y');
            // adding user object
            $returnable['user'] = (object)$question['user']->getAttributes();
            return (object)$returnable;
        });
        return response()->json(['questions' => $returnable], 200);
    }

    public function index(Request $request)
    {
        $queryResult = Question::where('id', $request->id)
            ->with('user:id,name,email', 'user_votes')->get()->first();
        // extract attributes for question
        $question = Arr::only($queryResult
            ->getAttributes(), ['id', 'title', 'content', 'created_at']);
        // formatting date attribute
        $question['created_at'] = Carbon::parse($question['created_at'])->format('jS M Y');
        // adding user data
        $question['user'] = (object)$queryResult->getRelation('user');
        // adding votes info
        $voteData = \App\Models\QuestionVote::getVote($queryResult);
        $question['vote'] = (object)$voteData['vote'];
        $question['isVoted'] = $voteData['isVoted'];
        // extract all answers
        $answers = \App\Models\Answer::getAnswer($queryResult);
        return response()->json([
            'question' => $question,
            'answers' => $answers], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $validatedData['user_id'] = auth()->id();
        $que = Question::create($validatedData);
        return response()->json(['message' => "'Question published at' $que->created_at"], 200);
    }
}
