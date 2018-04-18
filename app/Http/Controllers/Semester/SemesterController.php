<?php

namespace App\Http\Controllers\Semester;

use App\Semester;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $semesters = Semester::all();
        return view('semester.index',compact('semesters'));
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
            'year_from' => 'date_format:"Y"|required',
            'year_to' => 'date_format:"Y"|required|after:year_from',
//            'date_start_to_mark' => 'date_format:"d-m-Y"',
////            'date_end_to_mark' => 'date_format:"d-m-Y"|after:date_start_to_mark',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $semester = new Semester();
            $semester->year_from = $request->year_from;
            $semester->year_to = $request->year_to;
            $semester->date_start_to_mark = $request->date_start_to_mark;
            $semester->date_end_to_mark = $request->date_end_to_mark;
            $semester->term = $request->term;
            $semester->save();
            return response()->json([
                'semester' => $semester,
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
        $semester = Semester::find($id);
        return view('department.semester.semester-detail', compact('semester'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $semester = Semester::find($id);
        return response()->json([
            'semester' => $semester,
            'status' => true
        ], 200);
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
            ['name' => 'required|min:6'],
            ['name.required' => "Vui lòng nhập tên Khoa",
                'name.min' => 'Tên khoa phải có ít nhất 6 kí tự',
                'name.unique' => 'Tên khoa đã tồn tại'
            ]
        );

        $semester = Semester::find($id);
        if (!empty($semester)) {
            $semester->name = $request->name;
            $semester->save();
            return response()->json([
                'semester' => $semester,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $semester = Semester::find($id);
        if (!empty($semester)) {
            $semester->delete();
            return response()->json([
                'semester' => $semester,
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);
    }
}
