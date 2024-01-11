<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\AnimalKind;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReportStoreRequest;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->wantsJson()){
            dd('es datatable');
        }
        dd('request normal');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('reports.form')
            ->with('record',new Report())
            ->with('kinds',AnimalKind::get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportStoreRequest $request)
    {
        $validated = $request->validated();

        $record = new Report($validated);
        $record->status = 'Pending';


        $lat = $validated['latitude'];
        $long= $validated['longitude'];

        $result = DB::table('departments as dep')
            ->selectRaw('dep.name as department_name, dep.capital, dep.id as department_id, dis.name as district_name, dis.id as district_id, ciu.id as city_id, ciu.name as city_name, ba.id as neighborhood_id, ba.name as neighborhood_name')
            ->leftJoin('districts as dis', function($join)use($lat,$long){
                $join->whereRaw("ST_Contains(dis.geom, ST_GeomFromText('POINT( $long $lat )',0))");
            })
            ->leftJoin('cities as ciu',function($join)use($lat,$long){
                $join->whereRaw("ST_Contains(ciu.geom, ST_GeomFromText('POINT( $long $lat)',0))");
            })
            ->leftJoin('neighborhoods as ba',function($join)use($lat,$long){
                $join->whereRaw("ST_Contains(ba.geom, ST_GeomFromText('POINT( $long $lat)',0))");
            })
            ->whereRaw("ST_Contains(dep.geom, ST_GeomFromText('POINT( $long $lat)',0))")
            ->whereRaw("ST_Contains(ciu.geom, ST_GeomFromText('POINT( $long $lat)',0))")
            ->whereRaw("ST_Contains(dis.geom, ST_GeomFromText('POINT( $long $lat)',0))")
            ;
            dd($result->get());

//         $result = DB::raw("SELECT dep.name, dep.capital, dis.name as district_name, ciu.name as city_name,ba.name as neighborhood_name,
//         dep.id as department_id, dis.id as district_id, ciu.id as city_id, ba.id as neighborhood_id
//   FROM departments dep
//   LEFT JOIN districts dis   ON ST_Contains(dis.geom, ST_GeomFromText('POINT( $long $lat  )',0))
//   LEFT JOIN cities ciu ON ST_Contains(ciu.geom, ST_GeomFromText('POINT($long $lat )',0))
//   LEFT JOIN neighborhoods ba ON ST_Contains(ba.geom, ST_GeomFromText('POINT( $long $lat )',0))
//   WHERE ST_Contains(dep.geom, ST_GeomFromText('POINT( $long $lat )',0))
//   AND ST_Contains(ciu.geom, ST_GeomFromText('POINT( $long $lat )',0))
//   AND ST_Contains(ciu.geom, ST_GeomFromText('POINT( $long $lat )',0))");
//   $result->get();
        /*SELECT dep.name, dep.capital, dis.name, ciu.name,ba.name,
        dep.id, dis.id, ciu.id, ba.id
  FROM departments dep
  LEFT JOIN districts dis   ON ST_Contains(dis.geom, ST_GeomFromText('POINT( -57.54522800445557 -25.376407092431045  )',0))
  LEFT JOIN cities ciu ON ST_Contains(ciu.geom, ST_GeomFromText('POINT( -57.54522800445557 -25.376407092431045 )',0))
  LEFT JOIN neighborhoods ba ON ST_Contains(ba.geom, ST_GeomFromText('POINT( -57.54522800445557 -25.376407092431045 )',0))
  WHERE ST_Contains(dep.geom, ST_GeomFromText('POINT( -57.54522800445557 -25.376407092431045 )',0))
  AND ST_Contains(ciu.geom, ST_GeomFromText('POINT( -57.54522800445557 -25.376407092431045 )',0))
  AND ST_Contains(ciu.geom, ST_GeomFromText('POINT( -57.54522800445557 -25.376407092431045 )',0)) */
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
