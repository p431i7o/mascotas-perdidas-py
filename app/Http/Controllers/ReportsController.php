<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\AnimalKind;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReportStoreRequest;
use Auth;
use Carbon\Carbon;

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
        // dd('request normal');
        return view('reports.index');
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

        $query = DB::table('departments as dep')
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
        $result = $query->get();
        if($result->count()>0){
            $record->department_id = $result[0]->department_id;
            $record->city_id = $result[0]->city_id;
            $record->district_id = $result[0]->district_id;
            $record->neighborhood_id = $result[0]->neighborhood_id;
        }
        $record->expiration = Carbon::now()->addDays(7);
        $record->user_id = Auth::user()->id;
        $record->attachments = json_encode([]);
        if($record->save()){
            return  redirect()->route('reports.index')->with('success', 'true')->with('message',__('Saved correctly'));
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        return view('reports.form')
            ->with('record', $report)
            ->with('kinds',AnimalKind::get());
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
