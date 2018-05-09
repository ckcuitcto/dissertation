<?php

namespace App\Http\Controllers\Transcript;

use App\Model\EvaluationForm;
use App\Model\Semester;
use App\Model\Student;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TranscriptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        //
        if ($user->Role->id >= 5) // admin va phong ctsv thì lấy tất cả user
        {
            $students = Student::all();
        } elseif ($user->Role->id >= 2) //ban can su lop, co van hoc tpa, chu nhiem khoa thì lấy user thuộc khoa giống
        {
            $users = array_flatten(User::where('faculty_id', $user->faculty_id)->where('role_id', '<=', 2)->select('id')->get()->toArray());
            $students = Student::whereIn('user_id', $users)->get();
        }

        return view('transcript.index', compact('students'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::find($id);
        $user = User::find($student->user_id);

        $evaluationForms = EvaluationForm::where('student_id', $id)->get();
        return view('transcript.show', compact('user', 'evaluationForms'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
