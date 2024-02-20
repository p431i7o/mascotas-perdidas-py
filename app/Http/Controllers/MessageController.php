<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Report;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->wantsJson()){
            $query = Message::where('to_user_id',auth()->user()->id)->with(['From','To']);
            if (!empty($request->search['value'])) {
                $query->where('message', 'ilike', '%' . $request->search['value'] . '%');
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
        return view('messages.index');
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $report = Report::find($request->report_id);
        $user_id = Auth::user()->id;
        $message = $request->message;

        Message::create([
            'from_user_id'=>$user_id,
            'to_user_id'=>$report->user_id,
            'message'=>$message,
            'status'=>'Sent',
            'report_id'=>$report->id
        ]);

        return back()->with('message','Enviado correctamente');
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\Message  $message
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Message $message)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Models\Message  $message
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Message $message)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\Message  $message
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Message $message)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
