<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportDenounce extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'report_id',
        'user_id',
        'comment'
    ];

    public function Rerport(){
        return $this->belongsTo('Report');

    }

    public function User(){
        return $this->belongsTo('User');
    }
}
