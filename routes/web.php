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
    Route::get('/home', 'Home\HomeController@index')->name('home');


    Route::group(['prefix' => 'khoa', 'middleware' => 'can:manage-faculty'], function () {
        Route::get('/', ['as' => 'faculty', 'uses' => 'Department\FacultyController@index']);
        Route::get('/{id}', ['as' => 'faculty-detail', 'uses' => 'Department\FacultyController@show']);

        Route::post('/store', ['as' => 'faculty-store', 'uses' => 'Department\FacultyController@store']);

        Route::get('/destroy/{id}', ['as' => 'faculty-destroy', 'uses' => 'Department\FacultyController@destroy']);

        // hien thi form
        Route::get('/edit/{id}', ['as' => 'faculty-edit', 'uses' => 'Department\FacultyController@edit']);
        //update
        Route::post('/update/{id}', ['as' => 'faculty-update', 'uses' => 'Department\FacultyController@update']);

    });

    Route::group(['prefix' => 'lop'], function () {
        Route::get('/{id}', ['as' => 'class-detail', 'uses' => 'Department\ClassController@show']);

        Route::post('/store', ['as' => 'class-store', 'uses' => 'Department\ClassController@store']);

        Route::get('/destroy/{id}', ['as' => 'class-destroy', 'uses' => 'Department\ClassController@destroy']);

        Route::get('/edit/{id}', ['as' => 'class-edit', 'uses' => 'Department\ClassController@edit']);
        Route::post('/update/{id}', ['as' => 'class-update', 'uses' => 'Department\ClassController@update']);

    });

    Route::group(['prefix' => 'sinh-vien'], function () {
        Route::get('/', ['as' => 'student', 'uses' => 'Student\StudentController@index']);

        Route::get('/{id}', ['as' => 'student-detail', 'uses' => 'Student\StudentController@show']);

        Route::post('/store', ['as' => 'student-store', 'uses' => 'Student\StudentController@store']);

        Route::get('/destroy/{id}', ['as' => 'student-destroy', 'uses' => 'Student\StudentController@destroy']);

        Route::post('/update/{id}', ['as' => 'student-update', 'uses' => 'Student\StudentController@update']);
        Route::get('/edit/{id}', ['as' => 'student-edit', 'uses' => 'Student\StudentController@edit']);

        Route::post('/import', ['as' => 'student-import', 'uses' => 'Student\StudentController@import']);
    });

    Route::group(['prefix' => 'vai-tro'], function () {
        // danh sach role
        Route::get('/', ['as' => 'role-list', 'uses' => 'Role\RoleController@index']);
        // vao xem chi tiet role, se co cac danh sach user thuoc role o day
//        Route::get('/{id}',['as' => 'role-detail','uses' => 'Role\RoleController@show']);
        // xoa 1 role
//        Route::group(['middleware' => 'role:admin'],function() {
        Route::get('/destroy/{id}', ['as' => 'role-destroy', 'uses' => 'Role\RoleController@destroy']);
        // them 1 role
        Route::post('/store', ['as' => 'role-store', 'uses' => 'Role\RoleController@store']);
        // chỉnh sửa role
        Route::post('/update/{id}', ['as' => 'role-update', 'uses' => 'Role\RoleController@update']);
        Route::get('/edit/{id}', ['as' => 'role-edit', 'uses' => 'Role\RoleController@edit']);
//        });
    });

    Route::group(['prefix' => 'quyen'], function () {
        // danh sach role
        Route::get('/', ['as' => 'permission-list', 'uses' => 'Permission\PermissionController@index']);

//        Route::group(['middleware' => 'can:'],function() {
        // vao xem chi tiet role, se co cac danh sach user thuoc role o day
        Route::get('/{id}', ['as' => 'permission-detail', 'uses' => 'Permission\PermissionController@show']);
        // xoa 1 role
        Route::get('/destroy/{id}', ['as' => 'permission-destroy', 'uses' => 'Permission\PermissionController@destroy']);
        // them 1 role
        Route::post('/store', ['as' => 'permission-store', 'uses' => 'Permission\PermissionController@store']);
        // chỉnh sửa role
        Route::post('/update/{id}', ['as' => 'permission-update', 'uses' => 'Permission\PermissionController@update']);
        Route::get('/edit/{id}', ['as' => 'permission-edit', 'uses' => 'Permission\PermissionController@edit']);
//        });
    });

    Route::group(['prefix' => 'hoc-ki'], function () {
        // danh sach
        Route::get('/', ['as' => 'semester-list', 'uses' => 'Semester\SemesterController@index']);
        // vao xem chi tiet role, se co cac danh sach user thuoc role o day
        Route::get('/{id}', ['as' => 'semester-detail', 'uses' => 'Semester\SemesterController@show']);
        // them 1
        Route::post('/store', ['as' => 'semester-store', 'uses' => 'Semester\SemesterController@store']);
        // xoa 1
        Route::get('/destroy/{id}', ['as' => 'semester-destroy', 'uses' => 'Semester\SemesterController@destroy']);
        // chỉnh sửa
        Route::post('/update/{id}', ['as' => 'semester-update', 'uses' => 'Semester\SemesterController@update']);
        Route::get('/edit/{id}', ['as' => 'semester-edit', 'uses' => 'Semester\SemesterController@edit']);
    });

    Route::group(['prefix' => 'thong-bao'], function () {
        // Route::get('/',['as' => 'notification-list',])
    });

    Route::group(['prefix' => 'bang-diem'], function () {
        Route::get('/', ['as' => 'transcript', 'uses' => 'Transcript\TranscriptController@index']);

        Route::get('/{id}', ['as' => 'class-detail', 'uses' => 'Transcript\TranscriptController@show']);

        Route::post('/store', ['as' => 'class-store', 'uses' => 'Transcript\TranscriptController@store']);

        Route::get('/destroy/{id}', ['as' => 'class-destroy', 'uses' => 'Transcript\TranscriptController@destroy']);

        Route::get('/edit/{id}', ['as' => 'class-edit', 'uses' => 'Transcript\TranscriptController@edit']);
        Route::post('/update/{id}', ['as' => 'class-update', 'uses' => 'Transcript\TranscriptController@update']);

    });

    Route::group(['prefix' => 'phieu-danh-gia'], function () {
        // danh sach
        Route::get('/', ['as' => 'evaluation-form', 'uses' => 'Evaluation\EvaluationFormController@index']);

        // tao moi form
//        Route::get('/{semesterId}', ['as' => 'evaluation-form-create', 'uses' => 'Evaluation\EvaluationFormController@create']);
        // vao xem chi tiet role, se co cac danh sach user thuoc role o day
        Route::get('/{id}', ['as' => 'evaluation-form-show', 'uses' => 'Evaluation\EvaluationFormController@show']);
        // them 1
//        Route::post('/store', ['as' => 'evaluation-form-store', 'uses' => 'Evaluation\EvaluationFormController@store']);
        // xoa 1
//        Route::get('/destroy/{id}', ['as' => 'evaluation-form-destroy', 'uses' => 'Evaluation\EvaluationFormController@destroy']);

        // chỉnh sửa
//        Route::post('/update/{id}', ['as' => 'evaluation-form-update', 'uses' => 'Evaluation\EvaluationFormController@update']);
//        Route::get('/edit/{id}', ['as' => 'evaluation-form-edit', 'uses' => 'Evaluation\EvaluationFormController@edit']);
    });

    Route::get('/thong-tin-ca-nhan', ['as' => 'personal-information', 'uses' => 'User\StudentController@index']);

    Route::group(['prefix' => 'y-kien'], function () {
        Route::get('/', ['as' => 'comment-create', 'uses' => 'Comment\CommentController@create'])->middleware('can:comment-add');

        Route::post('/store', ['as' => 'comment-store', 'uses' => 'Comment\CommentController@store'])->middleware('can:comment-add');

        Route::post('/reply/{id}', ['as' => 'comment-reply', 'uses' => 'Comment\CommentController@reply']);
        Route::get('/show/{id}', ['as' => 'comment-show', 'uses' => 'Comment\CommentController@show']);

        Route::get('/destroy/{id}', ['as' => 'comment-destroy', 'uses' => 'Comment\CommentController@destroy'])->middleware('can:comment-delete');

        Route::get('/danh-sach-y-kien', ['as' => 'comment-list', 'uses' => 'Comment\CommentController@index'])->middleware('can:comment-list');

    });

    Route::get('/thoi-khoa-bieu', ['as' => 'schedule', 'uses' => 'Home\HomeController@schedule']);
    Route::get('/thong-bao', ['as' => 'notification', 'uses' => 'Notification\NotificationController@notification']);
    Route::get('/hoc-phi', ['as' => 'tuition', 'uses' => 'Home\HomeController@tuition']);
    Route::get('/phong-dao-tao', ['as' => 'office-academic', 'uses' => 'Home\HomeController@officeAcademic']);
    Route::get('/quan-ly-minh-chung', ['as' => 'proofs', 'uses' => 'Home\HomeController@proofs']);

    Route::get('/tin-tuc-su-kien', ['as' => 'news', 'uses' => 'News\NewsController@news']);

    Route::get('/phong-ban', ['as' => 'departmentlist', 'uses' => 'Departmentlist\DepartmentlistController@departmentlist']);
});
