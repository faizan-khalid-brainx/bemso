<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadParticipant extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function roles()
    {
        return $this->hasOne(Role::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
