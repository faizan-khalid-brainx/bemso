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
        $this->hasOne(Role::class);
    }

}
