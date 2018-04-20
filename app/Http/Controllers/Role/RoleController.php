<?php

namespace App\Http\Controllers\Role;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('role.index',compact('roles'));
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
            'name' => 'required|min:6|unique:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'arrMessages' => $validator->errors()
            ],200);
        }else{
            $role = new Role();
            $role->name = $request->name;
            $role->save();
            return response()->json([
                'role' => $role,
                'status' => 'success'
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
        $role = Role::find($id);
        $roles = Role::all();
        return view('role.role-detail', compact('role','roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        return response()->json([
            'role' => $role,
            'status' => 'success'
        ]);
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
        $this->validate($request,
            ['name' => 'required|min:6|unique:faculties,name'],
            [   'name.required' => "Vui lòng nhập tên Khoa",
                'name.min' => 'Tên khoa phải có ít nhất 6 kí tự',
                'name.unique' => 'Tên khoa đã tồn tại'
            ]
        );
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();

        return redirect()->back()->with(['flash_message' => 'Sửa thành công']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if(!empty($role)){
            $role->delete();
            return response()->json([
                'role' => $role,
                'status' => true
            ]);
        }
        return response()->json([
            'status' => false
        ]);
    }
}
