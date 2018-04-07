<?php

Route::get('/', function () {
    return view('welcome');
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


Route::get('/home', 'HomeController@index')->name('home');

Route::get('/login-animated', 'HomeController@showLoginAnimatedForm')->name('loginAnimated');

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