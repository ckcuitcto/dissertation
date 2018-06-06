<?php

namespace App\Http\Controllers\User;

use App\Model\Faculty;
use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(25);
        $roles = Role::all();
        $faculties = Faculty::all();
        return view('user.index',compact('users','roles','faculties'));
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
        $arrRule = [
            'name' => 'required',
            'gender' => 'required',
            'role_id' => 'required',
            'users_id' => 'unique:users,users_id|required|string|size:10',
//            'classes_id' => 'sometimes|required'
        ];
        if(!empty($request->email)){
            $arrRule['email'] = 'unique:users,email';
        }
        $validator = Validator::make($request->all(), $arrRule,[
            'name.required' => "Vui lòng nhập tên",
            'email.unique' => "Email đã tồn tại",
            'gender.required' => "Vui lòng nhập giới tính",
            'role_id.required' => "Vui lòng chọn vai trò",
            'users_id.required' => "Vui lòng nhập ID",
            'users_id.size' => "Id có độ dài là 10 kí tự,bắt đầu với 2 chữ cái định danh(CD,DH,...)",
            'users_id.string' => "Id phải là một chuỗi",
            'users_id.unique' => "Id đã tồn tại",
//            'classes_id.required' => "Vui lòng chọn lớp.(Nếu lớp rỗng. vui lòng tạo lớp trước",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $user = new User();
            $user->users_id = $request->users_id;
            $user->name = $request->name;
            $user->status = $request->status;
            $user->role_id = $request->role_id;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->faculty_id = $request->faculty_id;
            $user->save();

            if($request->role_id == ROLE_SINHVIEN OR $request->role_id == ROLE_BANCANSULOP ){
                $user->Student()->update(['class_id' => $request->classes_id ]);
            }

            return response()->json([
                'status' => true
            ], 200);

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = DB::table('users')
            ->join('students', 'users.users_id', '=', 'students.user_id')
            ->select(
                'users.role_id','users.faculty_id',
                'users.id','users.users_id',
                'users.name','users.status',
                'users.gender','users.status',
                'students.class_id as classes_id','users.email'
            )
            ->where('users_id',$id)->first();

        if(empty($user)){
            return response()->json([
                'status' => false
            ],200);
        }
        return response()->json([
            'user' => $user,
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
        $arrRule = [
            'name' => 'required',
            'gender' => 'required',
            'role_id' => 'required',
            'users_id' => 'required|string|size:10|unique:users,users_id,'.$id.',users_id',
//            'classes_id' => 'sometimes|required'
        ];
        if(!empty($request->email)){
            $arrRule['email'] = 'unique:users,email';
        }
        $validator = Validator::make($request->all(), $arrRule,[
            'name.required' => "Vui lòng nhập tên",
            'email.unique' => "Email đã tồn tại",
            'gender.required' => "Vui lòng chọn giới tính",
            'role_id.required' => "Vui lòng chọn vai trò",
            'users_id.required' => "Vui lòng nhập ID",
            'users_id.size' => "Id có độ dài là 10 kí tự,bắt đầu với 2 chữ cái định danh(CD,DH,...)",
            'users_id.string' => "Id phải là một chuỗi",
            'users_id.unique' => "Id đã tồn tại",
//            'classes_id.required' => "Vui lòng chọn lớp.(Nếu lớp rỗng. vui lòng tạo lớp trước",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $user = User::where('users_id',$id)->first();
            $user->users_id = $request->users_id;
            $user->name = $request->name;
            $user->status = $request->status;
            $user->role_id = $request->role_id;
            $user->email = $request->email;
            $user->gender = $request->gender;
            if($request->role_id != ROLE_PHONGCONGTACSINHVIEN OR $request->role_id == ROLE_ADMIN ){
                $user->faculty_id = $request->faculty_id;
            }
            if($request->role_id == ROLE_SINHVIEN OR $request->role_id == ROLE_BANCANSULOP ){
                $user->Student()->update(['class_id' => $request->classes_id ]);
            }

            $user->save();
            return response()->json([
                'status' => true
            ], 200);

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
        //
    }
}
