<?php

namespace App\Http\Controllers\Import;

use App\Model\FileImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    public function index(){
        $imports = FileImport::all();
        return view('import.index',compact('imports'));
    }
}
