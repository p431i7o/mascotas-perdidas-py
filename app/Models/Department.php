<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    // use HasFactory;
    protected $fillable = [
        'name',
        'capital'
    ];

    public function Reports(){
        return $this->hasMany(Report::class,'department_id','id');
    }
}
