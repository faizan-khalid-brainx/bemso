<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AnswerVote extends Pivot
{
    // overrides laravel default database naming convention for Eloquent
    protected $table = 'answer_vote';
    protected $guarded = [];
    public $timestamps = false;
    use HasFactory;

    public static function getVote(Answer $answer)
    {
        $Voted = -1;
        $vote = $answer->getRelation('user_votes')->map(function ($user) use (&$Voted) {
            if ($user->getOriginal()['id'] == auth()->id()) {
                $Voted = $user->getOriginal()['pivot_vote'];
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
        return ['isVoted' => $Voted, 'vote' => $vote];
    }

}
