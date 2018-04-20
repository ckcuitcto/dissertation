<?php

namespace App\Http\Controllers\Permission;

use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('permission.index',compact('permissions'));
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
            'name' => 'required|unique:permissions,name',
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'arrMessages' => $validator->errors()
            ],200);
        }else{
            $permission = new Permission();
            $permission->name = $request->name;
            $permission->title = $request->title;
            $permission->save();
            return response()->json([
                'permission' => $permission,
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
        $permission = Permission::find($id);
        return response()->json([
            'permission' => $permission,
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
            [
                'name' => 'required|unique:faculties,name',
                'title' => 'required'
            ],
            [   'name.required' => "Vui lòng nhập tên Khoa",
                'name.min' => 'Tên khoa phải có ít nhất 6 kí tự',
                'name.unique' => 'Tên khoa đã tồn tại',
                'title' => 'Vui lòng nhập tiêu đề'
            ]
        );
        $permission = Permission::find($id);
        $permission->name = $request->name;
        $permission->save();

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
        $permission = Permission::find($id);
        if(!empty($permission)){
            $permission->delete();
            return response()->json([
                'permission' => $permission,
                'status' => true
            ]);
        }
        return response()->json([
            'status' => false
        ]);
    }
}
