<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function root(Request $request)
    {
        return view('welcome')->with('reportes', Report::where('status','active')->where('expiration','>',Carbon::now())->get());
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
}
