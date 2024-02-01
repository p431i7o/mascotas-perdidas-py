<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    // use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function Reports(){
        return $this->hasMany(Report::class,'district_id','id');
    }
}
