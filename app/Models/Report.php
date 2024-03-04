<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'animal_kind_id',
        'type',
        'date',
        'description',
        'expiration',
        'address',
        'latitude',
        'longitude',
        'status',
        'department_id',
        'city_id',
        'district_id',
        'neighborhood_id',
        'user_id',
        'approved_by',
        'approved_date',
        'observations',
        'attachments',
        'views',
        'renewed',
        // 'reported',
        'log'
    ];

    protected $casts = [
        'date'=>'datetime'
    ];

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => __($value??"")
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => __($value??"")
        );
    }

    public function Author(){
        return $this->hasOne('User','id','user_id');
    }

    public function Approved_by(){
        return $this->hasOne('User','id','approved_by');
    }

    public function Department(){
        return $this->hasOne(Department::class,'id','department_id');
    }

    public function City(){
        return $this->hasOne(City::class,'id','city_id');
    }

    public function District(){
        return $this->hasOne(District::class,'id','district_id');
    }

    public function Neighborhood(){
        return $this->hasOne(Neighborhood::class,'id','neighborhood_id');
    }

    public function Denounces(){
        return $this->hasMany(ReportDenounce::class,'report_id','id');
    }


}
