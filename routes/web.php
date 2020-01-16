<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'IndexController@index')->name('index');
Route::get('student/cabinet', 'StudentCabinetController@index')->name('student-cabinet-index');
Route::get('lecturer/cabinet', 'TeacherCabinetController@index')->name('teacher-cabinet-index');

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('admin/students/register', 'Auth\StudentRegisterController@showRegistrationForm')->name('student-register');
Route::post('admin/students/register', 'Auth\StudentRegisterController@register');
Route::get('admin/lecturers/register', 'Auth\TeacherRegisterController@showRegistrationForm')->name('teacher-register');;
Route::post('admin/lecturers/register', 'Auth\TeacherRegisterController@register');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin', function () {

    return view('admin.base.base');
});

Route::resource('admin/students', 'Admin\Students\StudentController', ['except' => [
    'create', 'store'
]]);

Route::resource('admin/lecturers', 'Admin\Teachers\TeacherController', ['except' => [
    'create', 'store'
]]);

Route::post('verification/login', 'HelpController@loginVerification');
