<?php
namespace App\Http\Controllers\Department;

use App\Model\Classes;
use App\Model\Role;
use App\Model\Staff;
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
            'staff_id' => 'required',
        ],[
            'name.required' => 'Bắt buộc nhập tên lớp',
            'name.unique' => 'Tên lớp đã bị trùng',
            'staff_id.required' => 'Bắt buộc chọn cố vấn học tập',
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
            ->leftJoin('users as u','s.user_id','u.users_id')
            ->select('u.users_id', 'u.name','s.id')
            ->where('u.faculty_id',$class->Faculty->id)
            ->orderBy('u.name')
            ->get();
        $roles = Role::where('weight','<=', ROLE_BANCANSULOP)->get();
        return view('department.class.class-detail',compact('class','staff','roles'));
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
            'name' => 'required|unique:classes,name',
            'staff_id' => 'required',
        ],[
            'name.required' => 'Bắt buộc nhập tên lớp',
            'name.unique' => 'Tên lớp đã bị trùng',
            'staff_id.required' => 'Bắt buộc chọn cố vấn học tập',
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:6',
            'staff_id' => 'required'
        ],[
            'name.required' => 'Bắt buộc nhập tên lớp',
            'staff_id.required' => 'Bắt buộc chọn cố vấn học tập',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ],200);
        }else{
            $class = Classes::find($id);
            $class->name = $request->name;
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

    public function getListClassByFaculty(Request $request){
        $id = $request->id;
        $classes = Classes::where('faculty_id', $id)->select('id','name')->get()->toArray();
        return response()->json([
            'classes' => $classes
        ],200);
    }

    public function getListClassByFacultyAddAll(Request $request){
        $id = $request->id;

        $classes = Classes::where('faculty_id', $id)->select('id','name')->get()->toArray();
        $classes = array_prepend($classes,array('id' => 0,'name' => 'Tất cả lớp'));

        return response()->json([
            'classes' => $classes
        ],200);
    }
}
