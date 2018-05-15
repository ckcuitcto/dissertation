<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\News;
use App\Model\Semester;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsList = News::all();
        $timeList = Semester::orderBy('id','desc')->first();
        return view('home.home', compact('newsList','timeList'));
    }

    public function showLoginAnimatedForm()
    {
        return view('auth.login-animated');
    }

}
