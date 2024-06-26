<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'from_user_id',
        'to_user_id',
        'message',
        'status',
        'read_at',
        'report_id'
    ];

    public function To(){
        return $this->hasOne(User::class,'id','to_user_id')->select(['id','name','email']);
    }

    public function From(){
        return $this->hasOne(User::class,'id','to_user_id')->select(['id','name','email']);
    }
}
