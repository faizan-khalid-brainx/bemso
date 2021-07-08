<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionVote extends Pivot
{
    // overrides laravel default database naming convention for Eloquent
    protected $table = 'question_vote';
    protected $guarded = [];
    use HasFactory;
}
