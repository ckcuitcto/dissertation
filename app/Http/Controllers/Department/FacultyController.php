<?php
namespace App\Http\Controllers\Department;
use App\Faculty;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

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
//        $faculties = DB::table('faculties as f')
//            ->leftJoin('classes as c','f.id','c.faculty_id')
//            ->leftJoin('staff as s','s.id','c.staff_id')
//            ->leftJoin('students as stu','stu.class_id','c.id')
//            ->select(DB::raw("COUNT(s.id) as totalStaff"), 'f.*',DB::raw("COUNT(stu.id) as totalStudent"))
//            ->groupBy('f.id')
//            ->get();
//dd($faculties);
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
        return view('department.faculty.faculty-detail', compact('faculty'));
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
        $this->validate($request,
            ['name' => 'required|min:6'],
            ['name.required' => "Vui lòng nhập tên Khoa",
                'name.min' => 'Tên khoa phải có ít nhất 6 kí tự',
                'name.unique' => 'Tên khoa đã tồn tại'
            ]
        );

        $faculty = Faculty::find($id);
        if (!empty($faculty)) {
            $faculty->name = $request->name;
            $faculty->save();
            return response()->json([
                'faculty' => $faculty,
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);

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


}
