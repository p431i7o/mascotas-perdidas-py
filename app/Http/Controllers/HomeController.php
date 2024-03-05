<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Department;
use App\Models\Neighborhood;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function root(Request $request)
    {
        $reports = Report::with(['Department','City','District','Neighborhood'])
                ->where('status','active')
                ->where('expiration','>',Carbon::now());
        $count = $reports->count();
        $start = 0;
        $limit = 8;

        $page = ($request->page??1);


        $start = $limit * $page - $limit;



        return view('welcome')
            ->with('reportes', $reports->limit($limit)->offset($start)->get())
            ->with('reportCount',$count)
            ->with('currentPage',$page)
            ->with('limit',$limit)
            ->with('start',$start);

    }

    public function index(Request $request)
    {
        return view('users.home');
    }

    public function help()
    {
        return view('help');
    }

    public function legal()
    {
        return view('legal');
    }

    public function search(Request $request){
        echo "Hola esto es search";
    }

    public function autoComplete(Request $request){

        $city = City::where('name','like','%'.$request->input('query').'%')->select('id','name')->get()->map(function($item){$item->name = 'Ciudad: '.$item->name;return $item;});
        $department = Department::where('name','like','%'.$request->input('query').'%')->select('id','name')->get()->map(function($item){$item->name = 'Departamento: '.$item->name;return $item;});
        $neiborghood = Neighborhood::where('name','like','%'.$request->input('query').'%')->select('id','name')->get()->map(function($item){$item->name = 'Barrio: '.$item->name;return $item;});

        $merge = $city->merge($department)->merge($neiborghood);
        return response()->json([
            "query"=> $request->input('query'),
            "suggestions"=>$merge->pluck('name'),// ['Bahamas', 'Bahrain', 'Bangladesh', 'Barbados'],
            "data"=> $merge
        ]);
    }
}
