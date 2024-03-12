<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\City;
use App\Models\Department;
use App\Models\Neighborhood;
use App\Models\Report;
use App\Models\User;
use Auth;
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
        $search = $request->input('search');
        $search_array = explode(' ',$search);
        $keywords = ['Ciudad:','Departamento:'];

        $query = Report::select('reports.*')->join('cities','cities.id','reports.city_id')->join('departments','departments.id','reports.department_id');
        foreach($keywords  as $keyword){
            if(strpos($search,$keyword)!== false){
                if($keyword=='Ciudad:'){
                    foreach($search_array as $sa){
                        if(!in_array($sa,$keywords)){
                            $query->orWhere('cities.name','like','%'.$sa.'%');
                        }
                    }
                }
                if($keyword=='Departamento:'){
                    foreach($search_array as $sa){
                        if(!in_array($sa,$keywords)){
                            $query->orWhere('departments.name','like','%'.$sa.'%');
                        }
                    }
                }
            }
        }
        $results = $query->get();
        return view('search.result')->with('results',$results);
    }

    public function autoComplete(Request $request){

        $city = City::where('name','like','%'.$request->input('query').'%')->select('id','name')->get()->map(function($item){$item->name = 'Ciudad: '.$item->name;return $item;});
        $department = Department::where('name','like','%'.$request->input('query').'%')->select('id','name')->get()->map(function($item){$item->name = 'Departamento: '.$item->name;return $item;});
        // $neiborghood = Neighborhood::where('name','like','%'.$request->input('query').'%')->select('id','name')->get()->map(function($item){$item->name = 'Barrio: '.$item->name;return $item;});

        $merge = $city->merge($department);//->merge($neiborghood);
        return response()->json([
            "query"=> $request->input('query'),
            "suggestions"=>$merge->pluck('name'),// ['Bahamas', 'Bahrain', 'Bangladesh', 'Barbados'],
            "data"=> $merge
        ]);
    }

    public function profile(Request $request){
        return view('profile')->with('user',Auth::user());
    }

    public function updateProfile(UpdateProfileRequest $request){
        $user = User::find(Auth::user()->id);
        $user->update($request->validated());
        return redirect()->route('profile')->with('message','Datos guardados correctamente!');

    }
}
