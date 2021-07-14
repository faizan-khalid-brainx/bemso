<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionVote extends Pivot
{
    // overrides laravel default database naming convention for Eloquent
    protected $table = 'question_vote';
    protected $guarded = [];
    public $timestamps = false;
    use HasFactory;

    public static function getVote($queryResult){
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
        return ['isVoted' => $isVoted, 'vote' => $vote];
    }
}
