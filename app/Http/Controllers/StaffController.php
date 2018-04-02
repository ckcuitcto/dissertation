<?php
/**
 * Created by PhpStorm.
 * User: Thai Duc
 * Date: 02-Apr-18
 * Time: 10:25 PM
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:staff');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('staff');
    }
}