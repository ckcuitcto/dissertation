<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Model\News;
use App\Model\Semester;
use Illuminate\Support\Facades\Auth;

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
        $userLogin = $this->getUserLogin();
        if($userLogin->Role->weight >= ROLE_PHONGCONGTACSINHVIEN ){
            $newsList = News::all();
        }else{
            $newsList = News::where( 'faculty_id','=',$userLogin->faculty_id)->orWhere('faculty_id','=',null)->orderBy('id','DESC')->limit(6)->get();
        }

        $timeList = $this->getCurrentSemester();
        return view('home.home', compact('newsList','timeList'));
    }

    public function showLoginAnimatedForm()
    {
        return view('auth.login-animated');
    }

}
