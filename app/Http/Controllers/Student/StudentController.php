<?php

namespace App\Http\Controllers\Student;

use App\Model\Role;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Excel;
use Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::where('role_id','<=',ROLE_BANCANSULOP)->get();

//        $users = DB::table('users')
//            ->leftJoin('roles','users.role_id','=','roles.id')
//            ->where('roles.weight', '<=', 2)
//            ->select('users.*')->get();

        return view('student.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //import students
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileImport' => 'required|',
        ], [
            'fileImport.required' => 'Bắt buộc chọn file',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {

            $arrFile = $request->file('fileImport');
            foreach ($arrFile as $file){
                if($file->getClientOriginalExtension() != "xlsx"){
                    $arrMessage = array("fileImport" => ["File ".$file->getClientOriginalName()." không hợp lệ "] );
                    return response()->json([
                        'status' => false,
                        'arrMessages' => $arrMessage
                    ], 200);
                }
            }
            foreach ($arrFile as $file){
//                $fileName = str_random(8) . "_" . $file->getClientOriginalName();
//                while(File::exists("upload/student/".$fileName)){
//                    $fileName = str_random(8) . "_" . $file->getClientOriginalName();
//                }
//                $file->move('upload/student/', $fileName);
                $result = Excel::load($file,function($reader){
                    $reader->all();
                })->get();
            }

            return response()->json([
                'semester' => $result,
                'status' => true
            ], 200);
        }
    }

}
