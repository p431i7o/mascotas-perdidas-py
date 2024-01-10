<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

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
        'neighborhood_id'
    ];

    public function getDepartamentoCiudadDistritoByLatLong($lat,$long){
        $result = DB::unprepared("SELECT dep.departamento_nombre, dep.departamento_capital, dis.distrito_nombre, ciu.ciudad_nombre,ba.barrio_nombre,
        dep.departamento_id, dis.distrito_id, ciu.ciudad_id, ba.barrio_id
  FROM departamentos dep
  LEFT JOIN distritos dis   ON ST_Contains(dis.geom, ST_GeomFromText('POINT( ".$this->db->escapeString($long)." ".$this->db->escapeString($lat)."  )',1))
  LEFT JOIN ciudades ciu ON ST_Contains(ciu.geom, ST_GeomFromText('POINT(".$this->db->escapeString($long)." ".$this->db->escapeString($lat)." )',1))
  LEFT JOIN barrios ba ON ST_Contains(ba.geom, ST_GeomFromText('POINT( ".$this->db->escapeString($long)." ".$this->db->escapeString($lat)." )',1))
  WHERE ST_Contains(dep.geom, ST_GeomFromText('POINT( ".$this->db->escapeString($long)." ".$this->db->escapeString($lat)." )',1))
  AND ST_Contains(ciu.geom, ST_GeomFromText('POINT( ".$this->db->escapeString($long)." ".$this->db->escapeString($lat)." )',1))
  AND ST_Contains(ciu.geom, ST_GeomFromText('POINT( ".$this->db->escapeString($long)." ".$this->db->escapeString($lat)." )',1))");
    }
}
