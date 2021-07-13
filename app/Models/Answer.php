<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'question_id', 'user_id'];

    public function user_votes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'answer_vote', 'answer_id',
            'user_id', 'id', 'id')->withPivot('vote');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getAnswer($queryResult)
    {
        $answers = $queryResult->answers()->with('user_votes')->get()->map(function ($answer) {
            // extracting answer attributes, its user and formatting date of answer
            $returnable = Arr::only($answer->getAttributes(), ['id', 'content', 'created_at']);
            $returnable['created_at'] = Carbon::parse($returnable['created_at'])->format('jS M Y');
            $returnable['user'] = (object)$answer->user()
                ->select(['id', 'name', 'email'])
                ->get()->first();
            $voteData = AnswerVote::getVote($answer);
            $returnable['vote'] = (object)$voteData['vote'];
            $returnable['isVoted'] = $voteData['isVoted'];
            return (object)$returnable;
        });
        return $answers;
    }

}
