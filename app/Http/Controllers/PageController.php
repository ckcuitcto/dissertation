<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function getIndex() {
        return view('page.trangchu');
    }

    public function getStudentInformation() {
        return view('page.thongtin_sinhvien');
    }
}
