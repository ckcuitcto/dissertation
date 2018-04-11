<?php

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/login-animated', 'Home\HomeController@showLoginAnimatedForm')->name('loginAnimated');


//Auth::routes();
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');
// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

Route::group(['middleware' => 'auth'],function(){
    Route::get('/home', 'Home\HomeController@index')->name('home');

    Route::prefix('staff')->group(function () {
        Route::get('/', 'StaffController@index')->name('staff.dashboard');
    });

    Route::get('/khoa',['as' => 'faculty', 'uses' => 'Department\FacultyController@index']);
    Route::group(['prefix' => 'khoa'],function(){
        Route::get('/{id}',['as' => 'faculty-detail', 'uses' => 'Department\FacultyController@show']);

        Route::post('/store',['as' => 'faculty-store', 'uses' => 'Department\FacultyController@store']);

        Route::get('/destroy/{id}',['as' => 'faculty-destroy', 'uses' => 'Department\FacultyController@destroy']);

        Route::post('/update/{id}',['as' => 'faculty-edit', 'uses' => 'Department\FacultyController@update']);
        Route::get('/ajax-bind-form/{id}',['as' => 'faculty-ajax-bind-form', 'uses' => 'Department\FacultyController@ajaxBindForm']);
    });

    Route::group(['prefix' => 'lop'],function() {
        Route::get('/{id}',['as' => 'class-detail', 'uses' => 'Department\ClassController@show']);
    });

    Route::get('/phieu-danh-gia', ['as' => 'evaluation-form', 'uses' => 'EvaluationFormController@index']);

    Route::get('/bang-diem', ['as' => 'transcript', 'uses' => 'Transcript\TranscriptController@index']);

    Route::get('/thong-tin-ca-nhan', ['as' => 'personal-information', 'uses' => 'User\StudentController@index']);

    Route::get('/y-kien', ['as' => 'comment', 'uses' => 'Home\HomeController@comment']);
    Route::get('/thoi-khoa-bieu', ['as' => 'schedule', 'uses' => 'Home\HomeController@schedule']);


    Route::get('index',[
        'as'=>'trang-chu',
        'uses'=>'PageController@getIndex'
    ]);

    Route::get('thong-tin-sinh-vien',[
        'as'=>'thongtinsinhvien',
        'uses'=>'PageController@getStudentInformation'
    ]);


    Route::get('thong-bao',[
        'as'=>'thongbao',
        'uses'=>'PageController@getNotification'
    ]);


    Route::get('hoc-phi',[
        'as'=>'hocphi',
        'uses'=>'PageController@getTuition'
    ]);

    Route::get('phong-dao-tao',[
        'as'=>'phongdaotao',
        'uses'=>'PageController@getOfficeAcademic'
    ]);
});
