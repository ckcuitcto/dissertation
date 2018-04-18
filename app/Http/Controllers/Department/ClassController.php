<?php
namespace App\Http\Controllers\Department;

use App\Classes;
use App\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:classes,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ],200);
        }else{
            $class = new Classes();
            $class->name = $request->name;
            $class->faculty_id = $request->faculty_id;
            $class->staff_id = $request->staff_id;
            $class->save();
            return response()->json([
                'class' => $class,
                'status' => true
            ],200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $class = Classes::find($id);
        $staff = DB::table('staff as s')
            ->leftJoin('users as u','s.user_id','u.id')
            ->select('u.id', 'u.name')
            ->where('u.faculty_id',$class->Faculty->id)
            ->orderBy('u.name')
            ->get();
        return view('department.class.class-detail',compact('class','staff'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $classes = Classes::find($id);
        return response()->json([
            'classes' => $classes,
            'status' => true
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:6',
            'staff_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ],200);
        }else{
            $class = Classes::find($id);
            $class->name = $request->name;
            $class->faculty_id = $request->faculty_id;
            $class->staff_id = $request->staff_id;
            $class->save();
            return response()->json([
                'class' => $class,
                'status' => true
            ],200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $class = Classes::find($id);
        if (!empty($class)) {
            $class->delete();
            return response()->json([
                'faculty' => $class,
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);
    }
}
