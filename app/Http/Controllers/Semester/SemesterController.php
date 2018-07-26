<?php

namespace App\Http\Controllers\Semester;

use App\Model\MarkTime;
use App\Model\Role;
use App\Model\Semester;
use App\Model\Student;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Validator;
use Yajra\DataTables\DataTables;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rolesCanMark = Role::whereHas('permissions', function ($query) {
            $query->where('name', 'like', '%can-mark%');
        })->orderBy('id')->get();

        return view('semester.index', compact('rolesCanMark'));
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

        $rolesCanMark = Role::whereHas('permissions', function ($query) {
            $query->where('name', 'like', '%can-mark%');
        })->orderBy('id')->get();
        $arrValidatorRole = array();
        $arrValidatorRoleMessage = array();
        foreach ($rolesCanMark as $role) {
            $dateEnd = "date_end_to_mark_" . $role->id;
            $dateStart = "date_start_to_mark_" . $role->id;

            $arrValidatorRole[$dateEnd] = "required";
            $arrValidatorRole[$dateStart] = "required";

            $arrValidatorRoleMessage[$dateEnd . ".required"] = 'Bắt buộc nhập thời gian bắt đầu chấm ';
            $arrValidatorRoleMessage[$dateStart . ".required"] = 'Bắt buộc nhập thời gian kết thúc chấm';

//            $arrValidatorRole[$dateStart] = "date|after_or_equal:" . $dateEnd;
//            $arrValidatorRole[$dateEnd] = "date";
//            $arrValidatorRoleMessage[$dateStart . ".after_or_equal"] = "Ngày bắt đầu <=  ngày kết thúc";
        }

        $arrValidatorRole['year_from'] = "required";
        $arrValidatorRole['year_to'] = "required";
        $arrValidatorRole['date_start'] = "required";
        $arrValidatorRole['date_end'] = "required";
        $arrValidatorRole['term'] = "required";

        $arrValidatorRole['date_start_to_request_re_mark'] = "required";
        $arrValidatorRole['date_end_to_request_re_mark'] = "required";
        $arrValidatorRole['date_start_to_re_mark'] = "required";
        $arrValidatorRole['date_end_to_re_mark'] = "required";

        $arrValidatorRoleMessage['year_to.after'] = 'Ngày kết thúc phải > ngày bắt đầu. ';
//        $arrValidatorRoleMessage['date_end.after'] = 'Ngày kết thúc phải > ngày bắt đầu. ';
        $arrValidatorRoleMessage['date_start.required'] = 'Bắt buộc nhập thời gian bắt đầu học kì ';
        $arrValidatorRoleMessage['date_end.required'] = 'Bắt buộc nhập thời gian kết thúc học kì ';
        $arrValidatorRoleMessage['year_from.required'] = 'Bắt buộc nhập năm học. ';
        $arrValidatorRoleMessage['year_to.required'] = 'Bắt buộc nhập năm học. ';
        $arrValidatorRoleMessage['term.required'] = 'Bắt buộc nhập học kì';
        $arrValidatorRoleMessage['date_start_to_request_re_mark.required'] = 'Bắt buộc nhập thời gian gửi yêu cầu phúc khảo';
        $arrValidatorRoleMessage['date_end_to_request_re_mark.required'] = 'Bắt buộc nhập thời gian kết thúc gửi yêu cầu phúc khảo';
        $arrValidatorRoleMessage['date_start_to_re_mark.required'] = 'Bắt buộc nhập thời gian bắt đầu chấm phúc khảo';
        $arrValidatorRoleMessage['date_end_to_re_mark.required'] = 'Bắt buộc nhập thời gian kết thúc chấm phúc khảo';

        $validator = Validator::make($request->all(), $arrValidatorRole, $arrValidatorRoleMessage);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $semester = Semester::where('year_from',$request->year_from)->where('year_to',$request->year_to)->where('term',$request->term)->first();
            if(empty($semester)) {
                // bắt lỗi = tay
                $arrMessage = $this->ruleSemester($request->all());
                if(!empty($arrMessage)){
                    return response()->json([
                        'status' => false,
                        'arrMessages' => $arrMessage
                    ], 200);
                }

                $semester = new Semester();
                $semester->year_from = $request->year_from;
                $semester->year_to = $request->year_to;
                if (!empty($request->date_start)) {
                    $semester->date_start = Carbon::createFromFormat('d/m/Y', $request->date_start);
                }
                if (!empty($request->date_end)) {
                    $semester->date_end = Carbon::createFromFormat('d/m/Y', $request->date_end);
                }
                if (!empty($request->date_start_to_re_mark)) {
                    $semester->date_start_to_re_mark = Carbon::createFromFormat('d/m/Y', $request->date_start_to_re_mark);
                }
                if (!empty($request->date_end_to_re_mark)) {
                    $semester->date_end_to_re_mark = Carbon::createFromFormat('d/m/Y', $request->date_end_to_re_mark);
                }
                if (!empty($request->date_end_to_request_re_mark)) {
                    $semester->date_end_to_request_re_mark = Carbon::createFromFormat('d/m/Y', $request->date_end_to_request_re_mark);
                }
                if (!empty($request->date_start_to_request_re_mark)) {
                    $semester->date_start_to_request_re_mark = Carbon::createFromFormat('d/m/Y', $request->date_start_to_request_re_mark);
                }
                $semester->term = $request->term;

                $arrRoleMarkTime = array();

                $dateStartMarkOfFirstRoleCanMark = null;
                $dateEndMarkOfLastRoleCanMark = null;
                $first = false;
                for ($i = 0; $i < count($rolesCanMark); $i++) {

                    $dateEnd = "date_end_to_mark_" . $rolesCanMark[$i]->id;
                    $dateStart = "date_start_to_mark_" . $rolesCanMark[$i]->id;

                    if ($first == false) {
                        $dateStartMarkOfFirstRoleCanMark = Carbon::createFromFormat('d/m/Y', $request->$dateStart);
                        $first = true;
                    }
                    if (count($rolesCanMark) - 1 == $i) {
                        $dateEndMarkOfLastRoleCanMark = Carbon::createFromFormat('d/m/Y', $request->$dateEnd);
                    }

                    $arrRoleMarkTime[] = [
                        'mark_time_start' => Carbon::createFromFormat('d/m/Y', $request->$dateStart),
                        'mark_time_end' => Carbon::createFromFormat('d/m/Y', $request->$dateEnd),
                        'role_id' => $rolesCanMark[$i]->id
                    ];
                }
                $semester->date_start_to_mark = $dateStartMarkOfFirstRoleCanMark;
                $semester->date_end_to_mark = $dateEndMarkOfLastRoleCanMark;

                $semester->save();
                // lưu thời gian chấm
                $semester->MarkTimes()->createMany($arrRoleMarkTime);

                return response()->json([
                    'semester' => $semester,
                    'status' => true
                ], 200);
            }else{
                $arrMessage = array("year_from" => ["Học kỳ này đã tồn tại"]);
                return response()->json([
                    'status' => false,
                    'arrMessages' => $arrMessage
                ], 200);
            }
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
        $markTimeBySemester = MarkTime::where('semester_id', $id)->get();
        $semester->date_start_to_mark = Carbon::parse($semester->date_start_to_mark)->format('d/m/Y');
        $semester->date_end_to_mark = Carbon::parse($semester->date_end_to_mark)->format('d/m/Y');

        if (!empty($semester->date_start)) {
            $semester->date_start = Carbon::parse($semester->date_start)->format('d/m/Y');
        }
        if (!empty($semester->date_end)) {
            $semester->date_end = Carbon::parse($semester->date_end)->format('d/m/Y');
        }

        if (!empty($semester->date_start_to_re_mark)) {
            $semester->date_start_to_re_mark = Carbon::parse($semester->date_start_to_re_mark)->format('d/m/Y');
        }
        if (!empty($semester->date_end_to_re_mark)) {
            $semester->date_end_to_re_mark = Carbon::parse($semester->date_end_to_re_mark)->format('d/m/Y');
        }

        if (!empty($semester->date_start_to_request_re_mark)) {
            $semester->date_start_to_request_re_mark = Carbon::parse($semester->date_start_to_request_re_mark)->format('d/m/Y');
        }
        if (!empty($semester->date_end_to_request_re_mark)) {
            $semester->date_end_to_request_re_mark = Carbon::parse($semester->date_end_to_request_re_mark)->format('d/m/Y');
        }

        foreach ($markTimeBySemester as $value) {
            $value->mark_time_start = Carbon::parse($value->mark_time_start)->format('d/m/Y');
            $value->mark_time_end = Carbon::parse($value->mark_time_end)->format('d/m/Y');
        }
        return response()->json([
            'semester' => $semester,
            'marktime' => $markTimeBySemester,
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
        $rolesCanMark = Role::whereHas('permissions', function ($query) {
            $query->where('name', 'like', '%can-mark%');
        })->orderBy('id')->get();
        $arrValidatorRole = array();
        $arrValidatorRoleMessage = array();
        foreach ($rolesCanMark as $role) {
            $dateEnd = "date_end_to_mark_" . $role->id;
            $dateStart = "date_start_to_mark_" . $role->id;

            $arrValidatorRole[$dateEnd] = "required";
            $arrValidatorRole[$dateStart] = "required";

            $arrValidatorRoleMessage[$dateEnd . ".required"] = " Bắt buộc nhập. ";
            $arrValidatorRoleMessage[$dateStart . ".required"] = "Bắt buộc nhập. ";

//            $arrValidatorRole[$dateEnd] = "required|before:" . $dateStart;
            // $arrValidatorRole[$dateEnd] = "required";

//            $arrValidatorRoleMessage[$dateEnd . ".before"] = "Ngày kết thúc phải > ngày bắt đầu";
            // $arrValidatorRoleMessage[$dateEnd . ".required"] = "Bắt buộc nhập";
        }

        $arrValidatorRole['year_from'] = "sometimes|required";
        $arrValidatorRole['year_to'] = "sometimes|required";
        $arrValidatorRole['term'] = "required";
//        $arrValidatorRole['year_to'] = "required|after:year_from";
//        $arrValidatorRoleMessage['year_to.after'] = 'Ngày kết thúc phải > ngày bắt đầu. ';

        $arrValidatorRole['date_start'] = "required";
        $arrValidatorRole['date_end'] = "required";
        $arrValidatorRole['date_start_to_request_re_mark'] = "required";
        $arrValidatorRole['date_end_to_request_re_mark'] = "required";
        $arrValidatorRole['date_start_to_re_mark'] = "required";
        $arrValidatorRole['date_end_to_re_mark'] = "required";

        $arrValidatorRoleMessage['year_to.after'] = 'Ngày kết thúc phải > ngày bắt đầu. ';
//        $arrValidatorRoleMessage['date_end.after'] = 'Ngày kết thúc phải > ngày bắt đầu. ';

        $arrValidatorRoleMessage['date_start.required'] = 'Bắt buộc nhập thời gian bắt đầu học kì ';
        $arrValidatorRoleMessage['date_end.required'] = 'Bắt buộc nhập thời gian kết thúc học kì ';
        $arrValidatorRoleMessage['year_from.required'] = 'Bắt buộc nhập năm học. ';
        $arrValidatorRoleMessage['year_to.required'] = 'Bắt buộc nhập năm học. ';
        $arrValidatorRoleMessage['term.required'] = 'Bắt buộc nhập học kì';

        $arrValidatorRoleMessage['date_start_to_request_re_mark.required'] = 'Bắt buộc nhập thời gian gửi yêu cầu phúc khảo';
        $arrValidatorRoleMessage['date_end_to_request_re_mark.required'] = 'Bắt buộc nhập thời gian kết thúc gửi yêu cầu phúc khảo';
        $arrValidatorRoleMessage['date_start_to_re_mark.required'] = 'Bắt buộc nhập thời gian bắt đầu chấm phúc khảo';
        $arrValidatorRoleMessage['date_end_to_re_mark.required'] = 'Bắt buộc nhập thời gian kết thúc chấm phúc khảo';

        $validator = Validator::make($request->all(), $arrValidatorRole, $arrValidatorRoleMessage);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        }

        $semester = Semester::find($id);
        if (!empty($semester)) {

            $arrMessage = $this->ruleSemester($request->all());
            if(!empty($arrMessage)){
                return response()->json([
                    'status' => false,
                    'arrMessages' => $arrMessage
                ], 200);
            }
//            $semester->year_from = $request->year_from;
//            $semester->year_to = $request->year_to;
            if (!empty($request->date_start_to_re_mark)) {
                $semester->date_start_to_re_mark = Carbon::createFromFormat('d/m/Y', $request->date_start_to_re_mark);
            }
            if (!empty($request->date_end_to_re_mark)) {
                $semester->date_end_to_re_mark = Carbon::createFromFormat('d/m/Y', $request->date_end_to_re_mark);
            }
            if (!empty($request->date_end_to_request_re_mark)) {
                $semester->date_end_to_request_re_mark = Carbon::createFromFormat('d/m/Y', $request->date_end_to_request_re_mark);
            }
            if (!empty($request->date_start_to_request_re_mark)) {
                $semester->date_start_to_request_re_mark = Carbon::createFromFormat('d/m/Y', $request->date_start_to_request_re_mark);
            }

            if (!empty($request->date_start)) {
                $semester->date_start = Carbon::createFromFormat('d/m/Y', $request->date_start);
            }
            if (!empty($request->date_end)) {
                $semester->date_end = Carbon::createFromFormat('d/m/Y', $request->date_end);
            }
            $semester->term = $request->term;
            $semester->save();

            $dateStartMarkOfFirstRoleCanMark = null;
            $dateEndMarkOfLastRoleCanMark = null;
            $first = false;
//            foreach ($rolesCanMark as $role) {
            for($i = 0 ; $i < count($rolesCanMark) ; $i++){

                $dateEnd = "date_end_to_mark_" . $rolesCanMark[$i]->id;
                $dateStart = "date_start_to_mark_" . $rolesCanMark[$i]->id;

                if($first == false){
                    $dateStartMarkOfFirstRoleCanMark = Carbon::createFromFormat('d/m/Y', $request->$dateStart);
                    $first = true;
                }
                if(count($rolesCanMark)-1 == $i){
                    $dateEndMarkOfLastRoleCanMark = Carbon::createFromFormat('d/m/Y', $request->$dateEnd);
                }

                MarkTime::updateOrCreate(
                    [
                        'semester_id' => $semester->id,
                        'role_id' => $rolesCanMark[$i]->id
                    ],
                    [
                        'mark_time_start' => Carbon::createFromFormat('d/m/Y', $request->$dateStart),
                        'mark_time_end' => Carbon::createFromFormat('d/m/Y', $request->$dateEnd)
                    ]
                );
            }

            $semester->date_start_to_mark = $dateStartMarkOfFirstRoleCanMark;
            $semester->date_end_to_mark = $dateEndMarkOfLastRoleCanMark;

            $semester->save();
            // lưu thời gian chấm

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
//            if(empty($semester->Proofs) AND empty($semester->EvaluationForm) AND  empty($semester->StudentListEachSemester) AND empty($semester->AcademicTranscripts))
//            {
                $semester->delete();
                //sau khi xóa học kì thì cũng xóa form đánh giá
                return response()->json([
                    'semester' => $semester,
                    'status' => true
                ], 200);
//            }
            return response()->json([
                'status' => false,
                'message' => 'Không được xóa học kì này'
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy học kì'
        ], 200);
    }

    public function ajaxGetSemesters()
    {
        $currentSemester = $this->getCurrentSemester();
        $semesters = DB::table('semesters')
            ->select(
                '*',
                DB::raw("CONCAT(semesters.year_from,'-',semesters.year_to) as semesterYear")
            );

        return DataTables::of($semesters)
            ->addColumn('action', function ($semester) {
                $linkEdit = route('semester-edit', $semester->id);
                $linkUpdate = route('semester-update', $semester->id);
                $linkDestroy = route('semester-destroy', $semester->id);

                $btnEdit = "<a style='color: white' title='Sửa học kì' data-semester-id='$semester->id' data-semester-edit-link='$linkEdit'
                data-semester-update-link='$linkUpdate' class='btn btn-primary update-semester'> <i class='fa fa-edit' aria-hidden='true'></i> </a>";

//                $btnDelete = "<a style='color: white' title='Xóa học kì' data-semester-id='$semester->id'  data-semester-delete-link='$linkDestroy'
//                class='btn btn-danger destroy-semester'> <i class='fa fa-trash' aria-hidden='true'></i></a>";
                return "<span class='bs-component'>$btnEdit</span> ";

            })
            ->setRowClass(function ($semester) use ($currentSemester) {
                return $semester->id == $currentSemester->id ? 'alert-success' : '';
            })
            ->make(true);
    }

    // tự kiểm tra hơp lệ = tay
    public function ruleSemester($arrRequest){

        $arrMessage = array();

//        $yearFrom = Carbon::createFromFormat('d/m/Y', $arrRequest['year_from']);
//        $yearTo = Carbon::createFromFormat('d/m/Y', $arrRequest['year_to']);
        foreach($arrRequest as $key => $value){
            if ($key != '_token' AND $key != 'year_from' AND $key != 'year_to' AND $key != 'term') {
                $arrRequest[$key] = Carbon::createFromFormat('d/m/Y', $value);

//                $formatYear = Carbon::createFromFormat('Y', $value);
//                if($yearFrom != $formatYear OR $yearTo != $formatYear ){
//                    $arrMessage["year_from"] = ["Các thời gian chấm điểm phải thuộc năm của học kì"];
//                    return $arrMessage;
//                }
            }
        }
        if(!empty($arrRequest['year_from']) AND !empty($arrRequest['year_to'])) {
            if ($arrRequest['year_from'] > $arrRequest['year_to']) {
                $arrMessage["year_from"] = ["Năm kết thúc phải lớn hơn năm bắt đầu"];
            }
        }
        if(strtotime($arrRequest['date_start']) > strtotime($arrRequest['date_end'])){
            $arrMessage["date_start"] = ["Ngày kết thúc phải lớn hơn ngày bắt đầu"];
        }

        if( strtotime($arrRequest['date_start_to_request_re_mark']) > strtotime($arrRequest['date_end_to_request_re_mark']) ){
            $arrMessage["date_start_to_request_re_mark"] = ["Ngày kết thúc phải lớn hơn ngày bắt đầu"];
        }

        if(strtotime($arrRequest['date_start_to_re_mark']) > strtotime($arrRequest['date_end_to_re_mark'])){
            $arrMessage["date_start_to_re_mark"] =["Ngày kết thúc phải lớn hơn ngày bắt đầu"];
        }

        if(!empty($arrRequest['date_start_to_mark_1']) AND !empty($arrRequest['date_end_to_mark_1'])) {
            if (strtotime($arrRequest['date_start_to_mark_1']) > strtotime($arrRequest['date_end_to_mark_1'])) {
                $arrMessage["date_start_to_mark_1"] = ["Ngày kết thúc phải lớn hơn ngày bắt đầu"];
            }
        }

        if(!empty($arrRequest['date_start_to_mark_2']) AND !empty($arrRequest['date_end_to_mark_2'])) {
            if (strtotime($arrRequest['date_start_to_mark_2']) > strtotime($arrRequest['date_end_to_mark_2'])) {
                $arrMessage["date_start_to_mark_2"] = ["Ngày kết thúc phải lớn hơn ngày bắt đầu"];
            }
        }
        if(!empty($arrRequest['date_start_to_mark_3']) AND !empty($arrRequest['date_end_to_mark_3'])) {
            if (strtotime($arrRequest['date_start_to_mark_3']) > strtotime($arrRequest['date_end_to_mark_3'])) {
                $arrMessage["date_start_to_mark_3"] = ["Ngày kết thúc phải lớn hơn ngày bắt đầu"];
            }
        }

        if(!empty($arrRequest['date_end_to_mark_1']) AND !empty($arrRequest['date_start_to_mark_2'])) {
            if (strtotime($arrRequest['date_end_to_mark_1']) > strtotime($arrRequest['date_start_to_mark_2'])) {
                $arrMessage["date_start_to_mark_2"] = ["Ngày kết thúc của vài trò chấm trước phải bé hơn ngày bắt đầu của vai trò chấm sau"];
            }
        }

        if(!empty($arrRequest['date_end_to_mark_2']) AND !empty($arrRequest['date_start_to_mark_3'])) {
            if (strtotime($arrRequest['date_end_to_mark_2']) > strtotime($arrRequest['date_start_to_mark_3'])) {
                $arrMessage["date_start_to_mark_3"] = ["Ngày kết thúc của vài trò chấm trước phải bé hơn ngày bắt đầu của vai trò chấm sau"];
            }
        }

        if(!empty($arrRequest['date_start_to_mark_1']) AND !empty($arrRequest['date_start'])) {
            if (strtotime($arrRequest['date_start_to_mark_1']) < strtotime($arrRequest['date_start'])) {
                $arrMessage["date_start_to_mark_1"] = ["Ngày bắt đầu chấm của các vai trò phải bé hơn thời gian bắt đầu học kì"];
            }
        }

        if(!empty($arrRequest['date_end_to_mark_3']) AND !empty($arrRequest['date_end'])) {
            if (strtotime($arrRequest['date_end_to_mark_3']) > strtotime($arrRequest['date_end'])) {
                $arrMessage["date_end_to_mark_3"] = ["Ngày bắt kết thúc chấm của các vai trò phải bé hơn thời gian kết thúc học kì"];
            }
        }

        if(!empty($arrRequest['date_end_to_mark_3']) AND !empty($arrRequest['date_end'])) {
            if (strtotime($arrRequest['date_end_to_mark_3']) > strtotime($arrRequest['date_end'])) {
                $arrMessage["date_start_to_request_re_mark"] = ["Ngày bắt đầu khiếu nại phải lớn hơn ngày kết thúc chấm của vai trò cuối cùng"];
            }
        }

        if(!empty($arrRequest['date_end_to_request_re_mark']) AND !empty($arrRequest['date_start_to_re_mark'])) {
            if (strtotime($arrRequest['date_end_to_request_re_mark']) > strtotime($arrRequest['date_start_to_re_mark'])) {
                $arrMessage["date_start_to_re_mark"] = ["Ngày bắt đầu chấm lại khiếu nại phải lớn hơn ngày kết thúc khiếu nại"];
            }
        }

        return $arrMessage;
    }

    public function ajaxGetSemestersForBackupImportant()
    {
        $currentSemester = $this->getCurrentSemester();
        $semesters = DB::table('semesters')
            ->select(
                '*',
                DB::raw("CONCAT(semesters.year_from,'-',semesters.year_to) as semesterYear")
            );

        return DataTables::of($semesters)
        ->addColumn('action', function ($semester) {
            $linkDestroy = route('backup-important-handle', $semester->id);
            $btnDelete = "<a style='color: white' title='Xóa học kì' data-semester-id='$semester->id'  data-semester-delete-link='$linkDestroy'
            class='btn btn-danger destroy-semester'> <i class='fa fa-trash' aria-hidden='true'></i></a>";
            return "<span class='bs-component'>$btnDelete</span> ";

        })
        ->setRowClass(function ($semester) use ($currentSemester) {
            return $semester->id == $currentSemester->id ? 'alert-success' : '';
        })
        ->make(true);
    }


    public function backupImportant(){
        return view('backup.backup-important');
    }

    public function backupImportantHandle($id){
        $semester = Semester::find($id);
        if (!empty($semester)) {
            \Artisan::call('backup:clean');
            \Artisan::call('backup:run',['--only-db'=>true]);

            $files = Storage::files('/http---diemrenluyenstu.cf-/');
            $lastFile = $files[ count($files) - 1 ];
            $lastFileName = explode('/',$lastFile);
            $lastFileName = $lastFileName[count($lastFileName)-1];

            $semester->delete();

            return response()->json([
                'semester' => $semester,
                'status' => true,
                'file_path' => url("/backupdb/$lastFile"),
                'file_name' => $lastFileName
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy học kì'
        ], 200);
    }
}
