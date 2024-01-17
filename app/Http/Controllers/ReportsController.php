<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\AnimalKind;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReportStoreRequest;
use App\Http\Requests\ReportUpdateRequest;
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
            $query = Report::where('user_id',auth()->user()->id)
                ->select(DB::raw('reports.*, departments.name as department_name, cities.name as city_name, districts.name as district_name, neighborhoods.name as neighborhood_name '))
                ->leftJoin('departments','departments.id','reports.department_id')
                ->leftJoin('cities','cities.id','reports.city_id')
                ->leftJoin('districts','districts.id','reports.district_id')
                ->leftJoin('neighborhoods','neighborhoods.id','reports.neighborhood_id');
            if (!empty($request->search['value'])) {
                $query->where('id', 'ilike', '%' . $request->search['value'] . '%');
                $query->orWhere('type', 'ilike', '%' . $request->search['value'] . '%');
            }

            $count = $query->count();
            if (isset($request->order)) {
                foreach ($request->order as $order) {
                    $query->orderBy(DB::raw($order['column']+1), $order['dir']);
                }
            } else {
                $query->orderBy('id', 'asc');
            }

            $query->limit($request->length)->offset($request->start);
            $data_result_set = $query->get();

            foreach ($data_result_set as $indice => $fila) {
                // $data_result_set[$indice]->roles = User::find($fila->id)->getRoleNames()->map(function($item,$key){return __($item);});
            }

            return response()->json([
                'data' => $data_result_set,
                'recordsFiltered' => $count,
                'recordsTotal' => $count,
                'success' => true,
                'params' => $_GET,
                'draw' => (int)$request->draw
            ]);
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
        $now = Carbon::now();
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
        $record->log= json_encode([$now->toISOString()=>['type'=>'created','user_id'=>auth()->user()->id]]);
        $save_result = $record->save();

        // dd($request->pictures);
        $picture_storage = $this->storeFiles($request,$record);

        if($save_result){
            return  redirect()->route('reports.index')->with('success', true)->with('message',__('Saved correctly'));
        }else{
            return redirect()->back()->withInput()->with('success',false)->with('message',__('Error saving'));
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
    public function update(ReportUpdateRequest $request, Report $report)
    {
        $validated = $request->validated();
        // $validated = $report->update($request->validated());
        $record = $report;
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
        $this->storeFiles($request,$record);


        $save_result = $record->update($validated);
        // dd($save_result);
        if($save_result){
            return  redirect()->route('reports.index')->with('success', true)->with('message',__('Saved correctly'));
        }else{
            return redirect()->back()->withInput()->with('success',false)->with('message',__('Error saving'));
        }
    }

    private function storeFiles(Request $request, Report $report){
        $data = [];
        foreach($request->pictures as $index=> $current_picture){
           $path = $current_picture->store('report_uploads');

            $fileInfo = pathinfo($path);
            $sha1_file = sha1_file($current_picture->getRealPath());
            $extension = $current_picture->getClientOriginalExtension();
            $tmpProperties = [
                'file_name' => $fileInfo['basename'],
                'original_name'=>$current_picture->getClientOriginalName(),
                'extension'=>$extension,
                'mime' => $current_picture->getClientMimeType(),
                'file_size'=>$current_picture->getSize(),
                'sha1_content'=>$sha1_file
            ];
            if($extension == 'jpg' || $extension == 'png'){

                // dd($tmpProperties);
                $imageSize = getimagesize($current_picture->getRealPath());
                if(@is_array($imageSize)){

                    $tmpProperties['width'] = $imageSize[0];
                    $tmpProperties['height'] = $imageSize[1];
                }
            }

            $properties = $tmpProperties;

            $data[] = $properties;

        }
        $report->attachments= json_encode($data);
        return $report->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        $current_user_id = Auth::user()->id;
        if($report->user_id != $current_user_id){
            abort(400);
        }

        $result= $report->delete();
        return  redirect()->route('reports.index')->with('success', $result)->with('message',__('Erased'));
    }
}
