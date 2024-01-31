<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'message',
        'status',
        'read_at'
    ];

    public function To(){
        return $this->hasOne('User','id','to_user_id');
    }

    public function From(){
        return $this->hasOne('User','id','to_user_id');
    }
}
