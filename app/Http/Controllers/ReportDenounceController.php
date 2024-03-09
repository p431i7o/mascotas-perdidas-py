<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportDenounce;
use App\Repositories\Permissions;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportDenounceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!Auth::user()->can(Permissions::MANAGE_DENOUNCES)){
            abort(401,'No permitido');
        }

        if($request->wantsJson()){
            $query = Report::where('status','Reported')->with('Denounces');
            $count = $query->count();
            if (!empty($request->search['value'])) {
                $query->where('name', 'ilike', '%' . $request->search['value'] . '%');
                $query->orWhere('description','ilike', '%' . $request->search['value'] . '%');
                $query->orWhere('address','ilike', '%' . $request->search['value'] . '%');
            }

            $countFiltered = $query->count();
            if (isset($request->order)) {
                foreach ($request->order as $order) {
                    $query->orderBy(DB::raw($order['column']+1), $order['dir']);
                }
            } else {
                $query->orderBy('created_at', 'asc');
            }

            $query->limit($request->length)->offset($request->start);
            $data_result_set = $query->get();

            // foreach ($data_result_set as $indice => $fila) { }

            return response()->json([
                'data' => $data_result_set,
                'recordsFiltered' => $countFiltered,
                'recordsTotal' => $count,
                'success' => true,
                'params' => $_GET,
                'draw' => (int)$request->draw
            ]);
        }
        return view('denounces.index');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Report $report)
    {
        $validated = $request->validate([
            'comment' => 'required|max:500'
        ]);
        //primero buscar si ya se denuncio con este user
        $user_id = Auth::user()->id;
        $count_records  = ReportDenounce::where('report_id',$report->id)->where('user_id',$user_id)->count();

        //nos interesa si aun no denuncio y el reporte es uno activo
        if($count_records <= 0  && $report->status == __('Active')){
            ReportDenounce::create([
                'report_id'=>$report->id,
                'user_id'=> $user_id,
                'comment'=>$validated['comment']
            ]);
            $count = ReportDenounce::where('report_id',$report->id)->count();
            if($count >= config('app.report_denounces_threshold',5)){
                $report->status = 'Reported';
                $report->save();
            }
        }
        //como sea se retorna exito
        return response()->json([
            'success'=>true
        ]);
    }

}
