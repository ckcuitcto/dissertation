<?php
namespace App\Http\Controllers\Departmentlist;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentlistController extends Controller
{
    public function departmentlist() {
        return view('departmentlist.index');
    }
}
