<?php

namespace App\Http\Controllers\Department;

use App\Model\Faculty;
use App\Http\Controllers\Controller;
use App\Model\News;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Yajra\DataTables\DataTables;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculties = Faculty::all();
        return view('department.faculty.index', compact('faculties'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:6|unique:faculties,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $faculty = new Faculty();
            $faculty->name = $request->name;
            $faculty->save();
            return response()->json([
                'faculty' => $faculty,
                'status' => true
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $faculty = Faculty::find($id);
        $listStaffByFacultyId = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->select("users.*")
            ->where('roles.weight', '=', ROLE_COVANHOCTAP)
            ->where('users.faculty_id', '=', $id)->get();
        return view('department.faculty.faculty-detail', compact('faculty', 'listStaffByFacultyId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $faculty = Faculty::find($id);
        return response()->json([
            'faculty' => $faculty,
            'status' => true
        ], 200);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:6|unique:faculties,name,' . $id . ',id',
        ], [
            'name.required' => "Vui lòng nhập tên Khoa",
            'name.min' => 'Tên khoa phải có ít nhất 6 kí tự',
            'name.unique' => 'Tên khoa đã tồn tại'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $faculty = Faculty::find($id);
            if (!empty($faculty)) {
                $faculty->name = $request->name;
                $faculty->save();
                return response()->json([
                    'faculty' => $faculty,
                    'status' => true
                ], 200);
            } else {
                return response()->json([
                    'status' => false
                ], 200);
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $faculty = Faculty::find($id);
        if (!empty($faculty)) {
            $faculty->delete();
            return response()->json([
                'faculty' => $faculty,
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);


    }


    public function ajaxGetFaculties()
    {

        $faculties = DB::table('faculties')
            ->leftJoin('classes', 'classes.faculty_id', '=', 'faculties.id')
            ->select(
                'faculties.*',
                DB::raw('count(classes.faculty_id) AS countClass')
            )->groupBy('faculties.id');

        return DataTables::of($faculties)
            ->addColumn('action', function ($faculty) {
                $result = null;

                $facultyId = $faculty->id;
                $linkEdit = route('faculty-edit', $facultyId);
                $linkUpdate = route('faculty-update', $facultyId);
                $buttonEdit = "<a style='color:white' id='btn-reply-remaking-show' class='btn btn-primary faculty-update' data-faculty-id='$facultyId'
                                data-faculty-edit-link='$linkEdit' data-faculty-update-link='$linkUpdate'title='Sửa khoa'>
                               <i class='fa fa-edit' aria-hidden='true'></i> </a>";
                $result = $buttonEdit;

                $news = News::where('faculty_id', $facultyId)->count();
                if ($faculty->countClass <= 0 AND $news <= 0) {
                    $linkDestroy = route('faculty-destroy', $facultyId);
                    $buttonDelete = "<a style='color:white' title='Xóa khoa' class='btn btn-danger faculty-destroy' data-faculty-id='$facultyId' data-faculty-link='$linkDestroy'>
                    <i class='fa fa-trash-o' aria-hidden='true'></i></a>";

                    $result .= $buttonDelete;
                }
                return "<p class='bs-component'>$result </p>";
            })
            ->make(true);
    }
}
