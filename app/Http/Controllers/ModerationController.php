<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\AnimalKind;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReportStoreRequest;
use App\Http\Requests\ReportUpdateRequest;
use App\Repositories\Permissions;
use Auth;
use Carbon\Carbon;

class ModerationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!Auth::user()->can(Permissions::MODERATE_REPORTS)){
            abort(401,'No permitido');
        }
        if($request->wantsJson()){
            $query  = Report::where('status','Pending')
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

        }else{
            return view('moderation.index');
        }

    }

    public function approve(Request $request){
        if(!Auth::user()->can(Permissions::MODERATE_REPORTS)){
            abort(401,'No permitido');
        }
        $report = Report::find($request->id);
        $report->approved_by = Auth::user()->id;
        $report->approved_date = Carbon::now();
        $report->status = 'Active';
        $report->expiration = Carbon::now()->addDays(7);

        // $result =$report->save();
        return response()->json([
            'success'=>$report->save()
        ]);
    }

    public function reject(Request $request){
        if(!Auth::user()->can(Permissions::MODERATE_REPORTS)){
            abort(401,'No permitido');
        }
        $report = Report::find($request->id);
        $report->approved_by = Auth::user()->id;
        $report->approved_date = Carbon::now();
        $report->status = 'Rejected';
        $report->observations = $request->reason;


        // $result =$report->save();
        return response()->json([
            'success'=>$report->save()
        ]);
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
        if(!Auth::user()->can(Permissions::MODERATE_REPORTS)){
            abort(401,'No permitido');
        }
    }
}
