<?php

namespace App\Http\Controllers\Semester;

use App\Semester;
use Carbon\Carbon;
use Faker\Provider\DateTime;
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
        return view('semester.index', compact('semesters'));
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
            'year_from' => 'date_format:"Y"|required',
            'year_to' => 'date_format:"Y"|required|after:year_from',
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
            if (!empty($request->date_start_to_mark)) {
                $semester->date_start_to_mark = Carbon::createFromFormat('d/m/Y', $request->date_start_to_mark);
            }
            if (!empty($request->date_end_to_mark)) {
                $semester->date_end_to_mark = Carbon::createFromFormat('d/m/Y', $request->date_end_to_mark);
            }
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
     * @param  int $id
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $semester = Semester::find($id);

        $semester->date_start_to_mark = Carbon::parse($semester->date_start_to_mark)->format('d/m/Y');
        $semester->date_end_to_mark = Carbon::parse($semester->date_end_to_mark)->format('d/m/Y');
        return response()->json([
            'semester' => $semester,
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
            'year_from' => 'date_format:"Y"|required',
            'year_to' => 'date_format:"Y"|required|after:year_from',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        }

        $semester = Semester::find($id);
        if (!empty($semester)) {
            $semester->year_from = $request->year_from;
            $semester->year_to = $request->year_to;
            if (!empty($request->date_start_to_mark)) {
                $semester->date_start_to_mark = Carbon::createFromFormat('d/m/Y', $request->date_start_to_mark);
            }
            if (!empty($request->date_end_to_mark)) {
                $semester->date_end_to_mark = Carbon::createFromFormat('d/m/Y', $request->date_end_to_mark);

            }
            $semester->term = $request->term;
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
     * @param  int $id
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
