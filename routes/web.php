<?php
//login
//Route::get('/', function () {return view('auth.login');});
//Route::get('/login-animated', 'Home\HomeController@showLoginAnimatedForm')->name('loginAnimated');

Route::get('/', ['uses' => 'Auth\LoginController@showLoginForm']);
//Auth::routes();
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');
// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/laravel-filemanager', '\Unisharp\Laravelfilemanager\controllers\LfmController@show');
    Route::post('/laravel-filemanager/upload', '\Unisharp\Laravelfilemanager\controllers\UploadController@upload');

    Route::get('/home', 'Home\HomeController@index')->name('home');

    Route::post('/change-password', ['as' => 'change-password', 'uses' => 'User\UserController@changePassword']);

    Route::group(['prefix' => 'khoa', 'middleware' => 'can:faculty-list'], function () {
        Route::get('/', ['as' => 'faculty', 'uses' => 'Department\FacultyController@index'])->middleware('can:faculty-change');
        Route::get('/{id}', ['as' => 'faculty-detail', 'uses' => 'Department\FacultyController@show']);

        Route::post('/store', ['as' => 'faculty-store', 'uses' => 'Department\FacultyController@store'])->middleware('can:faculty-change');

        Route::get('/destroy/{id}', ['as' => 'faculty-destroy', 'uses' => 'Department\FacultyController@destroy'])->middleware('can:faculty-change');

        // hien thi form
        Route::get('/edit/{id}', ['as' => 'faculty-edit', 'uses' => 'Department\FacultyController@edit'])->middleware('can:faculty-change');
        //update
        Route::post('/update/{id}', ['as' => 'faculty-update', 'uses' => 'Department\FacultyController@update'])->middleware('can:faculty-change');

        Route::post('/get-faculties', ['as' => 'ajax-get-faculties', 'uses' => 'Department\FacultyController@ajaxGetFaculties']);
        Route::post('/get-faculty-detail', ['as' => 'ajax-get-class-by-faculty-detail', 'uses' => 'Department\FacultyController@ajaxGetClassByFacultyDetail']);

    });

//    Route::group(['prefix' => 'lop','middleware' => 'can:manage-class'], function () {
    Route::group(['prefix' => 'lop'], function () {
        Route::get('/{id}', ['as' => 'class-detail', 'uses' => 'Department\ClassController@show'])->middleware('can:manage-class');

        Route::post('/store', ['as' => 'class-store', 'uses' => 'Department\ClassController@store'])->middleware('can:manage-class');

        Route::get('/destroy/{id}', ['as' => 'class-destroy', 'uses' => 'Department\ClassController@destroy'])->middleware('can:manage-class');

        Route::get('/edit/{id}', ['as' => 'class-edit', 'uses' => 'Department\ClassController@edit'])->middleware('can:manage-class');
        Route::post('/update/{id}', ['as' => 'class-update', 'uses' => 'Department\ClassController@update'])->middleware('can:manage-class');

        Route::post('/get-list-by-faculty', ['as' => 'class-get-list-by-faculty', 'uses' => 'Department\ClassController@getListClassByFaculty']);
        Route::post('/get-list-by-faculty-none', ['as' => 'class-get-list-by-faculty-none', 'uses' => 'Department\ClassController@getListClassByFacultyAddAll']);

        // lấy ra danh sách lớp theo học kì theo người đang dăgd nhập
//        Route::post('/get-list-by-semester-and-userlogin-none', ['as' => 'class-get-list-by-semester-and-userlogin-none', 'uses' => 'Department\ClassController@getListClassBySemesterAndUser']);

        Route::post('/get-students-by-class', ['as' => 'ajax-get-students-by-class', 'uses' => 'Department\ClassController@ajaxGetStudentByClass']);


    });

    Route::group(['prefix' => 'sinh-vien'], function () {
//        Route::get('/', ['as' => 'student', 'uses' => 'Student\StudentController@index']);

        Route::get('/{id}', ['as' => 'student-detail', 'uses' => 'Student\StudentController@show']);

        Route::post('/store', ['as' => 'student-store', 'uses' => 'Student\StudentController@store']);

        Route::get('/destroy/{id}', ['as' => 'student-destroy', 'uses' => 'Student\StudentController@destroy']);

        Route::post('/update/{id}', ['as' => 'student-update', 'uses' => 'Student\StudentController@update']);

        Route::get('/edit/{id}', ['as' => 'student-edit', 'uses' => 'Student\StudentController@edit']);

        Route::post('/get-users', ['as' => 'ajax-student-get-users', 'uses' => 'Student\StudentController@ajaxGetUsers']);

        Route::post('/import', ['as' => 'student-import', 'uses' => 'Student\StudentController@import']);
        Route::post('/import-student-list-each-semester', ['as' => 'import-student-list-each-semester', 'uses' => 'Student\StudentController@importStudentListEachSemester']);
    });

    Route::group(['prefix' => 'vai-tro','middleware' => 'can:manager-role'], function () {
        // danh sach role
        Route::get('/', ['as' => 'role-list', 'uses' => 'Role\RoleController@index']);
        // xoa 1 role
//        Route::group(['middleware' => 'role:admin'],function() {
        Route::get('/destroy/{id}', ['as' => 'role-destroy', 'uses' => 'Role\RoleController@destroy']);
        // them 1 role
        Route::post('/store', ['as' => 'role-store', 'uses' => 'Role\RoleController@store']);
        // chỉnh sửa role
        Route::post('/update/{id}', ['as' => 'role-update', 'uses' => 'Role\RoleController@update']);
        Route::get('/edit/{id}', ['as' => 'role-edit', 'uses' => 'Role\RoleController@edit']);

//        Route::post('/get-roles', ['as' => 'ajax-get-roles', 'uses' => 'Permission\PermissionController@ajaxGetRoles']);

//        });
    });

    Route::group(['prefix' => 'quyen','middleware' => 'can:permission-role'], function () {
        // danh sach role
        Route::get('/', ['as' => 'permission-list', 'uses' => 'Permission\PermissionController@index']);

//        Route::group(['middleware' => 'can:'],function() {
        // vao xem chi tiet role, se co cac danh sach user thuoc role o day
        Route::get('/{id}', ['as' => 'permission-detail', 'uses' => 'Permission\PermissionController@show']);
        // xoa 1 role
        Route::get('/destroy/{id}', ['as' => 'permission-destroy', 'uses' => 'Permission\PermissionController@destroy'])->middleware('can:user-rights');
        // them 1 role
        Route::post('/store', ['as' => 'permission-store', 'uses' => 'Permission\PermissionController@store'])->middleware('can:user-rights');
        // chỉnh sửa role
        Route::post('/update/{id}', ['as' => 'permission-update', 'uses' => 'Permission\PermissionController@update'])->middleware('can:user-rights');
        Route::get('/edit/{id}', ['as' => 'permission-edit', 'uses' => 'Permission\PermissionController@edit'])->middleware('can:user-rights');

        Route::post('/get-permissions', ['as' => 'ajax-get-permissions', 'uses' => 'Permission\PermissionController@ajaxGetPermissions']);

//        });
    });

    Route::group(['prefix' => 'hoc-ki','middleware' => 'can:semester-change'], function () {
        // danh sach
        Route::get('/', ['as' => 'semester-list', 'uses' => 'Semester\SemesterController@index']);
        // vao xem chi tiet role, se co cac danh sach user thuoc role o day
        Route::get('/{id}', ['as' => 'semester-detail', 'uses' => 'Semester\SemesterController@show']);
        // them 1
        Route::post('/store', ['as' => 'semester-store', 'uses' => 'Semester\SemesterController@store'])->middleware('can:semester-change');
        // xoa 1
        Route::get('/destroy/{id}', ['as' => 'semester-destroy', 'uses' => 'Semester\SemesterController@destroy'])->middleware('can:semester-change');
        // chỉnh sửa
        Route::post('/update/{id}', ['as' => 'semester-update', 'uses' => 'Semester\SemesterController@update'])->middleware('can:semester-change');
        Route::get('/edit/{id}', ['as' => 'semester-edit', 'uses' => 'Semester\SemesterController@edit'])->middleware('can:semester-change');

        Route::post('/get-semesters', ['as' => 'ajax-get-semesters', 'uses' => 'Semester\SemesterController@ajaxGetSemesters']);

    });

    Route::group(['prefix' => 'bang-diem'], function () {
        Route::get('/danh-sach', ['as' => 'transcript', 'uses' => 'Transcript\TranscriptController@index'])->middleware('can:can-list-student-transcript');

        Route::get('/{id}', ['as' => 'transcript-show', 'uses' => 'Transcript\TranscriptController@show']);

        Route::post('/store', ['as' => 'transcript-store', 'uses' => 'Transcript\TranscriptController@store']);

        Route::get('/destroy/{id}', ['as' => 'transcript-destroy', 'uses' => 'Transcript\TranscriptController@destroy']);

        Route::get('/edit/{id}', ['as' => 'transcript-edit', 'uses' => 'Transcript\TranscriptController@edit']);
        Route::post('/update/{id}', ['as' => 'transcript-update', 'uses' => 'Transcript\TranscriptController@update']);

        Route::post('/get-users', ['as' => 'ajax-transcript-get-users', 'uses' => 'Transcript\TranscriptController@ajaxGetUsers']);

        Route::get('/', ['as' => 'academic-transcript', 'uses' => 'Transcript\TranscriptController@academicTranscript'])->middleware('can:view-academic-score');
        Route::post('/get-academic-transcript', ['as' => 'ajax-academic-transcript', 'uses' => 'Transcript\TranscriptController@ajaxGetAcademicTranscript'])->middleware('can:view-academic-score');
        Route::post('/xuat-bang-diem-sinh-vien', ['as' => 'export-academic-transcript', 'uses' => 'Export\ExportController@exportAcademicTranscriptLevel1'])->middleware('can:view-academic-score');
    });

    Route::group(['prefix' => 'phieu-danh-gia'], function () {
        // danh sach
        Route::get('/', ['as' => 'evaluation-form', 'uses' => 'Evaluation\EvaluationFormController@index']);

        // tao moi form
//        Route::get('/{semesterId}', ['as' => 'evaluation-form-create', 'uses' => 'Evaluation\EvaluationFormController@create']);
        // vao xem chi tiet role, se co cac danh sach user thuoc role o day
        Route::get('/{id}', ['as' => 'evaluation-form-show', 'uses' => 'Evaluation\EvaluationFormController@show']);
        // lưu kết quả
        Route::post('/update/{id}', ['as' => 'evaluation-form-update', 'uses' => 'Evaluation\EvaluationFormController@update'])->middleware('can:can-mark,App\Model\EvaluationForm');

        //kiem tra file upload = ajax
        Route::post('/upload', ['as' => 'evaluation-form-upload', 'uses' => 'Proof\ProofController@checkFileUpload']);

        Route::post('/get-file/{id}', ['as' => 'evaluation-form-get-file', 'uses' => 'Evaluation\EvaluationFormController@getProofById']);

        Route::post('/kiem-tra-nhap', ['as' => 'evaluation-form-check-input', 'uses' => 'Evaluation\EvaluationFormController@checkInput']);

    });

    Route::group(['prefix' => 'phuc-khao'], function () {
        Route::get('/', ['as' => 'remaking', 'uses' => 'Evaluation\ReMakingController@index'])->middleware('can:manage-remaking');

        Route::post('/store', ['as' => 'remaking-store', 'uses' => 'Evaluation\ReMakingController@store']);

        Route::get('/reply/{id}', ['as' => 'remaking-reply', 'uses' => 'Evaluation\ReMakingController@reply'])->middleware('can:manage-remaking');

        Route::get('/edit/{id}', ['as' => 'remaking-edit', 'uses' => 'Evaluation\ReMakingController@edit'])->middleware('can:manage-remaking');

        Route::post('/update/{id}', ['as' => 'remaking-update', 'uses' => 'Evaluation\ReMakingController@update'])->middleware('can:manage-remaking');

        Route::post('/get-remakings', ['as' => 'ajax-remakings', 'uses' => 'Evaluation\ReMakingController@ajaxGetRemakings'])->middleware('can:manage-remaking');

    });


    Route::group(['prefix' => 'thong-tin-ca-nhan'], function () {

        Route::get('/{id}', ['as' => 'personal-information-show', 'uses' => 'Information\InformationController@show']);

        Route::post('/update/{id}', ['as' => 'personal-information-update', 'uses' => 'Information\InformationController@update']);

        Route::post('/upload', ['as' => 'personal-information-upload', 'uses' => 'Information\InformationController@checkFileUpload']);

    });

    Route::group(['prefix' => 'minh-chung'], function () {
        Route::get('/', ['as' => 'proof', 'uses' => 'Proof\ProofController@index'])->middleware('can:proofs-list');
        Route::get('/danh-sach', ['as' => 'proof-list', 'uses' => 'Proof\ProofController@list'])->middleware('can:proofs-list-student');

        Route::get('/destroy/{id}', ['as' => 'proof-destroy', 'uses' => 'Proof\ProofController@destroy'])->middleware('can:proofs-delete');
        Route::post('/update-valid-proof-file/{id}', ['as' => 'update-valid-proof-file', 'uses' => 'Proof\ProofController@updateValidProofFile']);

        Route::post('/get-file/{id}', ['as' => 'evaluation-form-get-file', 'uses' => 'Proof\ProofController@edit']);

        Route::post('/update/{id}', ['as' => 'proof-update', 'uses' => 'Proof\ProofController@update']);

        Route::post('/store', ['as' => 'proof-store', 'uses' => 'Proof\ProofController@store']);

        Route::post('/get-proofs', ['as' => 'ajax-get-proofs', 'uses' => 'Proof\ProofController@ajaxGetProofs'])->middleware('can:proofs-list');
        Route::post('/get-proofs-of-student', ['as' => 'ajax-get-proofs-of-student', 'uses' => 'Proof\ProofController@ajaxGetProofsOfStudent'])->middleware('can:proofs-list-student');
    });

    Route::post('/get-files', ['as' => 'ajax-get-files', 'uses' => 'Import\ImportController@ajaxGetFiles']);


    Route::group(['prefix' => 'y-kien'], function () {
        Route::get('/', ['as' => 'comment-create', 'uses' => 'Comment\CommentController@create'])->middleware('can:comment-add');

        Route::post('/store', ['as' => 'comment-store', 'uses' => 'Comment\CommentController@store'])->middleware('can:comment-add');

        Route::post('/reply/{id}', ['as' => 'comment-reply', 'uses' => 'Comment\CommentController@reply'])->middleware('can:comment-reply');
        Route::get('/show/{id}', ['as' => 'comment-show', 'uses' => 'Comment\CommentController@show']);

        Route::get('/destroy/{id}', ['as' => 'comment-destroy', 'uses' => 'Comment\CommentController@destroy'])->middleware('can:comment-delete');

        Route::get('/danh-sach-y-kien', ['as' => 'comment-list', 'uses' => 'Comment\CommentController@index']);

        Route::post('/get-comments', ['as' => 'ajax-get-comments', 'uses' => 'Comment\CommentController@ajaxGetComments']);


    });

    Route::group(['prefix' => 'tin-tuc'], function () {

        Route::get('/', ['as' => 'news', 'uses' => 'News\NewsController@index'])->middleware('can:can-change-news');
        
        Route::post('/store', ['as' => 'news-store', 'uses' => 'News\NewsController@store'])->middleware('can:can-change-news');
        Route::get('/create', ['as' => 'news-create', 'uses' => 'News\NewsController@create'])->middleware('can:can-change-news');

        // hiển thi jchi tiết sẽ là cái này
        Route::get('/{title}-{id}', ['as' => 'news-show', 'uses' => 'News\NewsController@show']);

        Route::post('/update/{id}', ['as' => 'news-update', 'uses' => 'News\NewsController@update'])->middleware('can:can-change-news');
        Route::get('/edit/{id}', ['as' => 'news-edit', 'uses' => 'News\NewsController@edit'])->middleware('can:can-change-news');

        Route::get('/destroy/{id}', ['as' => 'news-destroy', 'uses' => 'News\NewsController@destroy'])->middleware('can:can-change-news');

        Route::post('/get-news', ['as' => 'ajax-get-news', 'uses' => 'News\NewsController@ajaxGetNews']);

    });

    Route::get('/phong-ban', ['as' => 'departmentlist', 'uses' => 'Departmentlist\DepartmentlistController@departmentlist']);

    Route::group(['prefix' => 'thong-bao'], function () {
        Route::get('/', ['as' => 'notifications', 'uses' => 'Notification\NotificationController@index']);

        Route::get('/show/{id}', ['as' => 'notifications-show', 'uses' => 'Notification\NotificationController@show']);

        Route::post('/get-notifications', ['as' => 'ajax-get-notifications', 'uses' => 'Notification\NotificationController@ajaxGetNotifications']);

    });

    Route::group(['prefix' => 'tai-khoan', 'middleware' => 'can:manage-user'], function () {

        Route::get('/', ['as' => 'user', 'uses' => 'User\UserController@index']);

//        Route::get('/{id}', ['as' => 'user-detail', 'uses' => 'User\UserController@show']);

        Route::post('/store', ['as' => 'user-store', 'uses' => 'User\UserController@store']);
        Route::get('/destroy/{id}', ['as' => 'user-destroy', 'uses' => 'User\UserController@destroy']);
        // hien thi form
        Route::get('/edit/{id}', ['as' => 'user-edit', 'uses' => 'User\UserController@edit']);
        //update
        Route::post('/update/{id}', ['as' => 'user-update', 'uses' => 'User\UserController@update']);

        Route::post('/get-users', ['as' => 'ajax-user-get-users', 'uses' => 'User\UserController@ajaxGetUsers']);

    });

    Route::group(['prefix' => 'files', 'middleware' => 'can:view-list-file-import'], function () {
        Route::get('/', ['as' => 'files', 'uses' => 'Import\ImportController@index']);
    });

    Route::group(['prefix' => 'xuat', 'middleware' => 'can:export-file'], function () {
        Route::post('/', ['as' => 'export-file', 'uses' => 'Export\ExportController@exportVer2']);
//        Route::post('/export-file-with-discipline', ['as' => 'export-file-with-discipline', 'uses' => 'Export\ExportController@exportWithDiscipline']);

        Route::get('/danh-sach', ['as' => 'export-file-list', 'uses' => 'Export\ExportController@index']);

        //. 1 expỏtt điểm đánh giá của lớp
        Route::post('/list-class', ['as' => 'ajax-get-class-export', 'uses' => 'Export\ExportController@ajaxGetClasses']);

        Route::get('/backup', ['as' => 'export-backup', 'uses' => 'Export\ExportController@backup']);

        Route::post('/list-user', ['as' => 'ajax-get-backup-users', 'uses' => 'Export\ExportController@ajaxGetUsers']);
        Route::post('/list-each-semester', ['as' => 'ajax-get-backup-each-semester', 'uses' => 'Export\ExportController@ajaxGetEachSemester']);
        Route::post('/list-faculty', ['as' => 'ajax-get-backup-faculty', 'uses' => 'Export\ExportController@ajaxGetFaculties']);

        //export backup
        Route::post('/list-export-class', ['as' => 'ajax-get-backup-class', 'uses' => 'Export\ExportController@ajaxGetBackUpClass']);

        Route::post('/list-export-semester', ['as' => 'ajax-get-backup-semester', 'uses' => 'Export\ExportController@ajaxGetBackUpSemester']);

        Route::post('/list-export-academic-transcript', ['as' => 'ajax-get-backup-academic-transcript', 'uses' => 'Export\ExportController@ajaxGetBackUpAcademicTranscript']);

    });

    Route::post('/xuat-danh-sach', ['as' => 'export-users', 'uses' => 'Export\ExportController@exportByUserId'])->middleware('can:export-users');

    Route::group(['prefix' => 'ki-luat', 'middleware' => 'can:import-discipline'], function () {
        Route::get('/', ['as' => 'discipline', 'uses' => 'Import\ImportController@discipline']);

        Route::post('/import-discipline', ['as' => 'import-discipline', 'uses' => 'Import\ImportController@importDiscipline']);

        Route::post('/list-discipline', ['as' => 'ajax-get-discipline', 'uses' => 'Import\ImportController@ajaxGetDiscipline']);


        Route::post('/add-academic-transcript', ['as' => 'add-academic-transcript', 'uses' => 'Transcript\TranscriptController@addAcademicTranscript']);
//        Route::post('/update-academic-transcript/{id}', ['as' => 'update-academic-transcript', 'uses' => 'Transcript\TranscriptController@updateAcademicTranscript']);
        Route::get('/edit-academic-transcript/{id}', ['as' => 'edit-academic-transcript', 'uses' => 'Transcript\TranscriptController@editAcademicTranscript']);

        // có 1 cái tương tự nhưng ở route khác. dùng lại. nhưng tránh sửa code thì thêm mới cái này
        Route::post('/get-classes-by-faculty', ['as' => 'get-classes-by-faculty', 'uses' => 'Department\ClassController@getListClassByFaculty']);
        Route::post('/get-students-by-class', ['as' => 'get-students-by-class', 'uses' => 'Department\ClassController@getStudentsByClass']);

        Route::post('/store', ['as' => 'discipline-reason-store', 'uses' => 'Discipline\DisciplineReasonController@store']);
        Route::get('/edit/{id}', ['as' => 'discipline-reason-edit', 'uses' => 'Discipline\DisciplineReasonController@edit']);
        Route::post('/update/{id}', ['as' => 'discipline-reason-update', 'uses' => 'Discipline\DisciplineReasonController@update']);
        Route::get('/destroy/{id}', ['as' => 'discipline-reason-destroy', 'uses' => 'Discipline\DisciplineReasonController@destroy']);
        Route::post('/get-discipline-reasons', ['as' => 'ajax-get-discipline-reasons', 'uses' => 'Discipline\DisciplineReasonController@ajaxGetDisciplineReason']);
    });


});
