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
                'status' => false,
                'arrMessages' => $validator->errors()
            ],200);
        }else{
            $permission = new Permission();
            $permission->name = $request->name;
            $permission->title = $request->title;
            $permission->save();
            return response()->json([
                'permission' => $permission,
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
            'status' => true
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required'
        ],[   'name.required' => "Vui lòng nhập tên quyền",
            'name.unique' => 'Tên quyền đã tồn tại',
            'title.required' => 'Vui lòng nhập tiêu đề'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ],200);
        }else{
            $permission = Permission::find($id);
            if (!empty($permission)) {
                $permission->name = $request->name;
                $permission->title = $request->title;
                $permission->save();
                return response()->json([
                    'permission' => $permission,
                    'status' => true
                ], 200);
            }
        }
        return response()->json([
            'status' => false
        ], 200);
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
