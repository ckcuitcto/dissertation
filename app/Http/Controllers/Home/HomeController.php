<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        return view('home.home');
    }

    public function showLoginAnimatedForm()
    {
        return view('auth.login-animated');
    }

    public function schedule(){
        return view('schedule.index');
    }    

    public function tuition() {
        return view('tuition.index');
    }

    public function officeAcademic() {
        return view('office-academic.index');
    }

}
