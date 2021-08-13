<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Thread extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'thread_participants','thread_id'
            ,'user_id','id','id');
    }

    public function threadParticipants()
    {
        return $this->hasMany(ThreadParticipant::class,'thread_id');
    }
}
