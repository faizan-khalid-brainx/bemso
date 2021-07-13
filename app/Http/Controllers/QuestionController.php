<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class QuestionController extends Controller
{

    public function display()
    {
        $questions = Question::with('user:id,email,name')
            ->select(['id', 'title', 'content', 'created_at','user_id'])
            ->withCount('answers', 'user_votes')->get()
            ->map(function ($question) {
                // removing foreign key
                unset($question['user_id']);
                // formatting date
                $question['created_at'] = Carbon::parse($question['created_at'])->format('jS M Y');
                // adding user object
                $question['user'] = (object)$question->getRelation('user')->get();
                return (object)$question;
            });
        return response()->json(['questions' => $questions], 200);
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
        // extract user attributes for question
        $question['user'] = (object)$queryResult->getRelation('user');
        // votes extracting question votes
        $isVoted = -1;
        $vote = $queryResult->getRelation('user_votes')->map(function ($user) use (&$isVoted) {
            if ($user->getOriginal()['id'] == auth()->id()) {
                $isVoted = $user->getOriginal()['pivot_vote'];
            }
            return $user->getOriginal()['pivot_vote'];
        });
        $vote = array_count_values($vote->toArray());
        if (!array_key_exists(0, $vote)) {
            $vote['0'] = 0;
        }
        if (!array_key_exists(1, $vote)) {
            $vote['1'] = 0;
        }
        $question['vote'] = (object)$vote;
        $question['isVoted'] = $isVoted;
        // extract all answers
        $answers = $queryResult->answers()->with('user_votes')->get()->map(function ($answer) {
            // extracting answer attributes, its user and formatting date of answer
            $Voted = -1;
            $returnable = Arr::only($answer->getAttributes(), ['id', 'content', 'created_at']);
            $returnable['created_at'] = Carbon::parse($returnable['created_at'])->format('jS M Y');
            $returnable['user'] = (object)$answer->user()
                ->select(['id', 'name', 'email'])
                ->get()->first();
            $vote = $answer->getRelation('user_votes')->map(function ($user) use (&$Voted) {
                if ($user->getOriginal()['id'] == auth()->id()) {
                    $Voted = $user->getOriginal()['pivot_vote'];
                }
                return $user->getOriginal()['pivot_vote'];
            });
            $returnable['isVoted'] = $Voted;
            $vote = array_count_values($vote->toArray());
            if (!array_key_exists(0, $vote)) {
                $vote['0'] = 0;
            }
            if (!array_key_exists(1, $vote)) {
                $vote['1'] = 0;
            }
            $returnable['vote'] = (object)$vote;
            return (object)$returnable;
        });
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
