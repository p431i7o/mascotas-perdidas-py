<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Carbon\Carbon;
use App\Models\Report;
use App\Models\AnimalKind;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ReportStoreRequest;
use App\Http\Requests\ReportUpdateRequest;
use App\Repositories\Permissions;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Laravel\Facades\Image;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;


class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|JsonResponse
    {
        if($request->wantsJson())
        {
            $query = Report::where('user_id',auth()->user()->id)
                ->select(DB::raw('reports.id,reports.type,reports.expiration,reports.attachments,reports.name, reports.status, departments.name as department_name, cities.name as city_name, districts.name as district_name, neighborhoods.name as neighborhood_name, now() as ct, case when now()>reports.expiration then "yes" else "no" end as expired'))
                ->leftJoin('departments','departments.id','reports.department_id')
                ->leftJoin('cities','cities.id','reports.city_id')
                ->leftJoin('districts','districts.id','reports.district_id')
                ->leftJoin('neighborhoods','neighborhoods.id','reports.neighborhood_id');
            if (!empty($request->search['value']))
            {
                $query->where('id', 'ilike', '%' . $request->search['value'] . '%');
                $query->orWhere('type', 'ilike', '%' . $request->search['value'] . '%');
            }

            $count = $query->count();
            if (isset($request->order))
            {
                foreach ($request->order as $order)
                {
                    $query->orderBy(DB::raw($order['column']+1), $order['dir']);
                }
            }
            else
            {
                $query->orderBy('id', 'asc');
            }

            $query->limit($request->length)->offset($request->start);
            $data_result_set = $query->get();

            foreach ($data_result_set as $indice => $fila)
            {
                $data_result_set[$indice]->link = route('reports.show', [$fila]);
            }

            return response()->json([
                'data'              => $data_result_set,
                'recordsFiltered'   => $count,
                'recordsTotal'      => $count,
                'success'           => true,
                'draw'              => (int)$request->draw
            ]);
        }

        return view('reports.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $record = new Report();

        if($request->input('type') && in_array($request->input('type'),['Found','Lost']))
        {
            $record->type = $request->input('type');
        }

        return view('reports.form')
            ->with('record', $record)
            ->with('kinds',AnimalKind::get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(ReportStoreRequest $request):RedirectResponse
    {
        $user_authenticated = auth()->user();
        DB::beginTransaction();
        $now = Carbon::now();
        $validated = $request->validated();

        $record = new Report($validated);
        $record->uuid = \Str::uuid();

        if(!$user_authenticated){
            $record->status = 'Pending';
            $record->email = $request->email;
        }else{
            $record->user_id = auth()->user()->id;
            $record->status = 'Active';
        }


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
        $record->expiration = Carbon::now()->addDays(config('app.renew_days_count'));
        $record->attachments = json_encode([]);
        $record->log= json_encode(
            [
                $now->toISOString()=>[
                    'type'=>'created',
                    'user_id'=>auth()->user()?auth()->user()->id:$request->email
                ]
            ]);
        $save_result = $record->save();

        $picture_storage = $this->storeFiles($request,$record);
        if(!$picture_storage){
            DB::rollBack();
            return redirect()->back()->withInput()->with('success',false)->with('message',__('Insufficient quantity of images'));
        }

        if($save_result){

            if(!$user_authenticated){
                $signedRouteForPublishing = URL::signedRoute('reports.publishFromMail', ['uuid'=>$record->uuid]);
                $signedRouteForEditing = Url::signedRoute('reports.editFromMail', ['uuid'=>$record->uuid]);
                $signedRouteForDeleting = URL::signedRoute('reports.deleteFromMail', ['uuid'=>$record->uuid]);
                Notification::route('mail', $record->email)
                    ->notify(new  \App\Notifications\EmailConfirmationForReport($signedRouteForPublishing,$signedRouteForEditing, $signedRouteForDeleting));
            }
            DB::commit();
            return  redirect()
                    ->route(auth()->user()?'reports.index':'root')
                    ->with('success', true)
                    ->with('message',__('Saved correctly').(!$user_authenticated?' Ahora revisa tu correo, y sigue las instrucciones para publicar tu reporte':''));
        }else{
            DB::rollBack();
            return redirect()->back()->withInput()->with('success',false)->with('message',__('Error saving'));
        }
    }

    public function edit(Report $report): View
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
     */
    public function update(ReportUpdateRequest $request, Report $report): RedirectResponse
    {
        $now = Carbon::now();
        $validated = $request->validated();

        $record = $report;
        $record->update($validated);
        $lat = $validated['latitude'];
        $long= $validated['longitude'];

        $user_id = $report->user_id?$report->user_id:'not_registered';

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
            $record->department_id  = $result[0]->department_id;
            $record->city_id        = $result[0]->city_id;
            $record->district_id    = $result[0]->district_id;
            $record->neighborhood_id = $result[0]->neighborhood_id;
        }
//        $this->storeFiles($request,$record);
        $current_log = json_decode($record->log,true    );
        $current_log[$now->toISOString()]=['type'=>'updated','user_id'=>$user_id];
        $record->log= json_encode($current_log);

        $save_result = $record->save();

        if($save_result){
            return  redirect()->route(auth()->user()?'reports.index':'root')
                ->with('success', true)
                ->with('message',__('Saved correctly'));
        }else{
            return redirect()->back()->withInput()->with('success',false)->with('message',__('Error saving'));
        }
    }

    private function storeFiles(Request $request, Report $report): bool
    {
        $data = [];

        $allowedExtensions = explode(',',config('app.allowed_picture_extensions','jpg,png,gif,jpeg'));
        $count = 0;
        foreach($request->pictures as $index=> $current_picture){
            $count++;
            if($count>config('app.number_of_pictures',5)){
                break;
            }
            $user_id = auth()->user()?$report->user_id:'not_registered';
            $path = $current_picture->store('report_uploads/'.$user_id.'/'.$report->id.'/originals');

            // create image manager with desired driver
            $manager = new ImageManager(new Driver());

            $image = $manager->read(config('filesystems.disks.local.root').'/'.$path);
            $image_thumb = $manager->read(config('filesystems.disks.local.root').'/'.$path);
            $image->scale(width: 640);
            $image_thumb->scale(width:250);
            $fileInfo = pathinfo($path);
            $sha1_file = sha1_file($current_picture->getRealPath());
            $extension = $current_picture->getClientOriginalExtension();
            if(!in_array($extension, $allowedExtensions)){
                abort(400,'Extension de imagen no admitida');
            }

            $image->save(sprintf(config('filesystems.disks.local.root').'/report_uploads/%s/%s/%s', $user_id, $report->id, $fileInfo['basename']));
            $image_thumb->save(sprintf(config('filesystems.disks.local.root').'/report_uploads/%s/%s/thumb_%s', $user_id, $report->id, $fileInfo['basename']));
            $tmpProperties = [
                'file_name' => $fileInfo['basename'],
                'original_name'=>$current_picture->getClientOriginalName(),
                'extension'=>$extension,
                'mime' => $current_picture->getClientMimeType(),
                'file_size'=>$current_picture->getSize(),
                'sha1_content'=>$sha1_file
            ];
            if($extension == 'jpg' || $extension == 'png')
            {
                $imageSize = getimagesize($current_picture->getRealPath());
                if(@is_array($imageSize))
                {
                    $tmpProperties['width'] = $imageSize[0];
                    $tmpProperties['height'] = $imageSize[1];
                }
            }
            $data[] = $tmpProperties;
        }
        if(empty($data))
        {
            return false;
        }
        $report->attachments= json_encode($data);
        return $report->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     */
    public function destroy(Request $request, Report $report): RedirectResponse|JsonResponse
    {

        $current_user_id = Auth::user()->id;

        if($report->user_id != $current_user_id && !Auth::user()->can(Permissions::MANAGE_DENOUNCES) ){
            abort(400);
        }
        $result = $this->doTheDestroy($report);
        if($request->wantsJson())
        {
            return response()->json(['success'=>$result]);
        }
        else
        {
            return  redirect()->route('reports.index')->with('success', $result)->with('message',__('Erased'));
        }
    }
    private function doTheDestroy(Report $report):bool
    {
        $now = Carbon::now();
        $current_log = json_decode($report->log,true);
        $user_id = auth()->user()?auth()->user()->id:'not_registered';
        $current_log[$now->toISOString()] = [
            'type'=>'deleted',
            'user_id'=>$user_id,
            'comment'=>$request->comment??''

        ];
        $report->log = json_encode($current_log);
        $report->save();
        $attachments = json_decode($report->attachments);

        foreach($attachments as $attachment){
            Storage::delete('report_uploads/'.$user_id.'/'.$report->id.'/originals/'.$attachment->file_name);
            Storage::delete('report_uploads/'.$user_id.'/'.$report->id.'/'.$attachment->file_name);
        }
        return $report->delete();
    }

    public function show(Request $request, Report $report): View
    {
        if($report->expiration< Carbon::now()){
            abort(404);
        }
        $report->views++;
        $report->save();

        return view('reports.show')->with('report',$report);

    }

    public function showImage(Request $request, Report $report, $index, $kind=null): \Illuminate\Http\Response
    {
        if($report->expiration < Carbon::now()){
            abort(404);
        }
        $attachments = json_decode($report->attachments);
        if(!isset($attachments[$index])){
            abort(400);
        }
        $user_id = $report->user_id??'not_registered';
        if($kind=='thumb')
        {
            $file = Storage::get('report_uploads/'.$user_id.'/'.$report->id.'/thumb_'.$attachments[$index]->file_name);
        }
        else
        {
            $file = Storage::get('report_uploads/'.$user_id.'/'.$report->id.'/'.$attachments[$index]->file_name);
        }
        return Response::make($file, 200)->header("Content-Type", $attachments[$index]->mime);
    }

    public function renovate(Request $request, Report $report): JsonResponse|RedirectResponse
    {
        $now = Carbon::now();
        $current_user_id = Auth::user()->id;
        if($report->user_id != $current_user_id ){
            abort(400);
        }
        $current_log = json_decode($report->log,true);

        $current_log[$now->toISOString()] = [
            'type'=>'renovated',
            'user_id'=>auth()->user()->id
        ];
        $report->log = json_encode($current_log);
        $report->expiration = Carbon::now()->addDays(config('app.renew_days_count'));
        $result = $report->save();

        if($request->wantsJson()){
            return response()->json(['success'=>$result]);
        }else{
            return  redirect()->route('reports.index')->with('success', $result)->with('message',__('Renewed'));
        }
    }

    public function publishFromMail(Request $request, $uuid): RedirectResponse
    {
        $report = Report::where('uuid','=',$uuid)->firstOrFail();
        $report->status = 'Active';
        $report->save();
        return redirect()->route('root')->with(['success'=>true,'message'=>__('Report published')]);
    }

    public function editFromMail(Request $request, $uuid): View
    {
        $record = Report::where('uuid',$uuid)->firstOrFail();
        return view('reports.form')
            ->with('record', $record)
            ->with('kinds',AnimalKind::get());
    }

    public function deleteFromMail(Request $request, $uuid): RedirectResponse
    {
        $record = Report::where('uuid',$uuid)->firstOrFail();
        $this->doTheDestroy($record);
        return redirect()->route('root')->with(['success'=>true,'message'=>__('Report deleted')]);
    }

    public function showPdf(Request $request, Report $report): \Illuminate\Http\Response
    {
        if($report->expiration < Carbon::now()){
            abort(404);
        }
        $attachments = [];
        foreach (json_decode($report->attachments) as $index => $value){
            $image =Image::read(Http::get(route('report.image.show', [$report->id, $index], true))->body());
            $image->resize(null,500);
            $attachments[] = $image;

        }
        //$attachments[] = $this->generateQRCode($report);
        $pdf = Pdf::loadView('reports.pdf', ['report' => $report,'attachments'=>$attachments,'qr'=>$this->generateQRCode($report)]);
        return $pdf->stream('reporte.pdf');
        //return $pdf->download('reporte.pdf');
    }

    private function generateQRCode(Report $report): string
    {
        $texto = url(route('reports.show', $report->id),true);

        // Configurar el renderizador
        $renderer = new ImageRenderer(
            new RendererStyle(400),          // Tamaño: 400x400 px
            new SvgImageBackEnd()            // Formato: SVG
        );

        // Generar el QR
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($texto);
        return $qrCode;
    }
}
