<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('users.home');
    }

    public function root(Request $request)
    {

        return view('welcome')->with('reportes', Report::get());
    }

    public function help()
    {
        echo "help";
    }

    public function legal()
    {
        echo "legal";
    }
}
