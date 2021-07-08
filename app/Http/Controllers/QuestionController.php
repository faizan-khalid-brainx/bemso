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
        $questions = Question::with('user')->withCount('answers','user_votes')->get()
            ->map(function ($question) {
            // create object and extract necessary attributes
            $returnable = Arr::only($question->getAttributes(),
                ['id', 'title', 'content', 'created_at','answers_count','user_votes_count']);
            // formatting date
            $returnable['created_at'] = Carbon::parse($returnable['created_at'])->format('jS M Y');
            $returnable['user'] = (object)Arr::only($question->getRelation('user')
                ->getAttributes(), ['id', 'name', 'email']);
            return (object)$returnable;
        });
        return response(json_encode(['questions' => $questions]),200);
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
