<?php

Route::get('/', function () {
    return view('auth.login');
});

//Auth::routes();
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');
// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');


Route::get('/home', 'Home\HomeController@index')->name('home');

Route::get('/login-animated', 'Home\HomeController@showLoginAnimatedForm')->name('loginAnimated');

Route::prefix('staff')->group(function () {
    Route::get('/login', 'Auth\StaffLoginController@showLoginForm')->name('staff.login');
    Route::post('/login', 'Auth\StaffLoginController@login')->name('staff.login.submit');
    Route::get('/', 'StaffController@index')->name('staff.dashboard');
});

Route::get('/phieu-danh-gia', ['as' => 'evaluation-form', 'uses' => 'EvaluationFormController@index']);

Route::get('/bang-diem', ['as' => 'transcript', 'uses' => 'Transcript\TranscriptController@index']);

Route::get('/thong-tin-ca-nhan', ['as' => 'personal-information', 'uses' => 'User\StudentController@index']);

Route::get('/y-kien', ['as' => 'comment', 'uses' => 'Home\HomeController@comment']);
Route::get('/thoi-khoa-bieu', ['as' => 'schedule', 'uses' => 'Home\HomeController@schedule']);

<<<<<<< HEAD
=======
Route::prefix('staff')->group(function() {
Route::get('/login', 'Auth\StaffLoginController@showLoginForm')->name('staff.login');
Route::post('/login', 'Auth\StaffLoginController@login')->name('staff.login.submit');
Route::get('/', 'StaffController@index')->name('staff.dashboard');
});

Route::get('index',[
    'as'=>'trang-chu',
    'uses'=>'PageController@getIndex'
]);

Route::get('thong-tin-sinh-vien',[
    'as'=>'thongtinsinhvien',
    'uses'=>'PageController@getStudentInformation'
]);

Route::get('phieu-danh-gia',[
    'as'=>'phieudanhgia',
    'uses'=>'PageController@getFormEvaluation'
]);

Route::get('thong-bao',[
    'as'=>'thongbao',
    'uses'=>'PageController@getNotification'
]);

Route::get('gop-y',[
    'as'=>'gopy',
    'uses'=>'PageController@getOpinion'
]);

Route::get('thoi-khoa-bieu',[
    'as'=>'thoikhoabieu',
    'uses'=>'PageController@getTimetable'
]);

Route::get('hoc-phi',[
    'as'=>'hocphi',
    'uses'=>'PageController@getTuition'
]);
>>>>>>> 55a8a76998a4a28fcb2d48782da8c2576497a34c
