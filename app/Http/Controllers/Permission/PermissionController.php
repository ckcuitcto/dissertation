<?php

namespace App\Http\Controllers\Permission;

use App\Model\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;
use Yajra\DataTables\DataTables;


class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('permission.index');
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
            'display_name' => 'required'
        ],[
            'name.required' => "Vui lòng nhập tên quyền",
            'name.unique' => "Tên đã bị trùng",
            'display_name.required' => 'Vui lòng nhập tên hiển thị'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ],200);
        }else{
            $permission = new Permission();
            $permission->name = $request->name;
            $permission->display_name = $request->display_name;
            $permission->description = $request->description;
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
            'display_name' => 'required'
        ],[
            'display_name.required' => 'Vui lòng nhập tiêu đề'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ],200);
        }else{
            $permission = Permission::find($id);
            if (!empty($permission)) {
                $permission->display_name = $request->display_name;
                $permission->description = $request->description;
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

    public function ajaxGetPermissions(){
        $permissions = DB::table('permissions')->select('id','name','display_name','description');

        return DataTables::of($permissions)
            ->addColumn('action', function ($permission) {
                $linkEdit = route('permission-edit',$permission->id);
                $linkUpdate = route('permission-update',$permission->id);
                $btnUpdate = "<a style='color: white' title='Sửa' data-permission-id='$permission->id' data-permission-edit-link='$linkEdit'
                data-permission-update-link='$linkUpdate' class='btn btn-primary permission-update'><i class='fa fa-edit' aria-hidden='true'></i> </a>";
                return "<p class='bs-component'>$btnUpdate</p> ";
            })
            ->make(true);
    }
}
