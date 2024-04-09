<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Report;
use Auth;
use Carbon\Carbon;
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
            $query = Message::where(function($query){
                $query->where('to_user_id',auth()->user()->id)
                    ->orWhere('from_user_id',auth()->user()->id);
            })
                //where('to_user_id',auth()->user()->id)
                ->where('parent_id',null)
                ->with(['From','To']);
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
        //@todo sanity checks
        $report = Report::find($request->report_id);
        $user_id = Auth::user()->id;
        $message =   $request->message ;
        $parent_id = null;

        $firstMessage = Message::where('from_user_id',$user_id)->where('to_user_id',$report->user_id)->where('report_id',$report->id)->orderBy('id','asc');
        if($firstMessage->count() > 0){
            $parent_id = $firstMessage->first()->id;
        }

        Message::create([
            'parent_id'=>$parent_id,
            'from_user_id'=>$user_id,
            'to_user_id'=>$report->user_id,
            'message'=>$message,
            'status'=>'Sent',
            'report_id'=>$report->id
        ]);

        return back()->with('message','Enviado correctamente');
    }

    public function respondMessage(Request $request, Message $message){
        //@todo sanity checks
        $user_id = Auth::user()->id;

        Message::create([
            'from_user_id'=>$user_id,
            'to_user_id'=>$message->to_user_id,
            'message'=>$request->message,
            'status'=>'Sent',
            'report_id'=>$message->report_id
        ]);
        return back()->with('message','Enviado correctamente');
    }

    public function getConversation(Request $request, Message $message){
        if($message->to_user_id != Auth::user()->id && $message->from_user_id != Auth::user()->id){
            abort(400);
        }

        $message->read_at = Carbon::now();
        $message->status = 'Read';

        $result = $message->save();
        $conversation = Message::where('report_id',$message->report_id)->orderBy('created_at','asc')->get();
        foreach($conversation as $index=>$value){
            $conversation[$index]->message = htmlentities($value->message);
        }
        return response()->json(['sucess'=>$result,'data'=>$conversation]);
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
        if($message->to_user_id!=Auth::user()->id){
            abort(400);
        }
        $result = $message->delete();
        return response()->json(['sucess'=>$result]);
    }
}
