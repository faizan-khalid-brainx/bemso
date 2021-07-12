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
}
