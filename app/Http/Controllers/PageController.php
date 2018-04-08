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

    public function getFormEvaluation() {
        return view('page.phieu_danh_gia');
    }

    public function getNotification() {
        return view('page.thong_bao');
    }

    public function getOpinion() {
        return view('page.gop_y');
    }

    public function getTimetable() {
        return view('page.thoi_khoa_bieu');
    }

    public function getTuition() {
        return view('page.hoc_phi');
    }
}
