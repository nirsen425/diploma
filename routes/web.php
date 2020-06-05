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
use App\User;
use App\Page;

Route::get('/', 'IndexController@index')->name('index');
Route::get('lecturer/show/{teacher}', 'IndexController@showTeacher')->name('teacher_show')->middleware('studentAccessForFullTeacherDescription');
Route::get('student/cabinet', 'StudentCabinetController@index')->name('student_cabinet_index');
Route::get('lecturer/cabinet', 'TeacherCabinetController@index')->name('teacher_cabinet_index');
Route::post('lecturer/cabinet/practice', 'TeacherCabinetController@getPracticeByDirectionCourse');
Route::post('lecturer/cabinet/files', 'TeacherCabinetController@getFilesByDirectionCourse');

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
//Route::get('admin/students/register', 'Auth\StudentRegisterController@showRegistrationForm')->name('student_register');
//Route::post('admin/students/register', 'Auth\StudentRegisterController@register');
//Route::get('admin/lecturers/register', 'Auth\TeacherRegisterController@showRegistrationForm')->name('teacher_register');
//Route::post('admin/lecturers/register', 'Auth\TeacherRegisterController@register');

Route::get('/admin', function () {
    return view('admin.base.base');
})->name('admin')->middleware('auth')->middleware('admin');

Route::resource('admin/students', 'Admin\Students\StudentController', ['except' => [
    'create', 'store'
]]);

Route::resource('admin/lecturers', 'Admin\Teachers\TeacherController', ['except' => [
    'create', 'store'
]])->parameters(['lecturers' => 'teacher']);

Route::resource('admin/groups', 'Admin\Groups\GroupController', ['only' => [
    'index', 'create', 'store'
]]);

Route::resource('admin/pages', 'Admin\Pages\PageController');

Route::post('verification/login/{user?}', 'HelpController@loginVerification')->middleware('admin');
Route::post('verification/title/{page?}', 'HelpController@titleVerification')->middleware('admin');
Route::post('upload/image', 'HelpController@uploadImage')->middleware('auth');

Route::get('page/{slug}', function($slug) {
    $page = Page::whereSlug($slug)->firstOrFail();

    return view('page', ['page' => $page]);
})->name('page')->middleware('auth');

// Заявки
Route::post('/application/confirm/{studentId}/{typeId}', 'ApplicationController@confirm')->name('application_confirm');
Route::post('/application/reject/{studentId}/{typeId}', 'ApplicationController@reject')->name('application_reject');
Route::post('/application/cancel/{teacherId}/{typeId}', 'ApplicationController@cancel')->name('application_cancel');
Route::post('/application/get-free-practice-places', 'ApplicationController@getFreePracticePlaces');
Route::post('application/{teacher}', 'ApplicationController@store')->name('application_store');
// Установка лимитов
Route::get('admin/set-limits/{year}', 'Admin\AdminApplicationsController@showTeacherLimitsPage')->name('teacher_limits');
Route::post('admin/set-limits', 'Admin\AdminApplicationsController@setLimits');
// Профиль преподавателя
Route::post('teacher/{teacher}/update/login', 'TeacherCabinetController@updateLogin')->name('teacher_login_update');
Route::post('teacher/{teacher}/update/password', 'TeacherCabinetController@updatePassword')->name('teacher_password_update');
Route::post('teacher/{teacher}/update/photo', 'TeacherCabinetController@updatePhoto')->name('teacher_photo_update');
Route::post('teacher/{teacher}/update/short-description', 'TeacherCabinetController@updateShortDescription')->name('teacher_short_description_update');
Route::post('teacher/{teacher}/update/full-description', 'TeacherCabinetController@updateFullDescription')->name('teacher_full_description_update');

// Профиль студента
Route::post('student/{student}/update/login', 'StudentCabinetController@updateLogin')->name('student_login_update');
Route::post('student/{student}/update/password', 'StudentCabinetController@updatePassword')->name('student_password_update');

// Заявки студентов
Route::get('admin/student-applications/{historyYear}/{groupStoryId?}', 'Admin\AdminApplicationsController@showStudentLastApplications')->name('student_applications');
Route::post('admin/student-applications', 'Admin\AdminApplicationsController@changeOrCreateApplication');
// Заявки руководителей
Route::get('admin/teacher-applications/{selectedYear}/{teacherId?}', 'Admin\AdminApplicationsController@showTeacherApplications')->name('teacher_applications');

// Получение отчета
Route::get('report/practice/group/{year?}/{groupStoryId?}', 'Admin\AdminReportController@getReportPracticeGroup')->name('report_practice_group');
Route::get('report/practice/teacher/{year?}/{teacherId?}', 'Admin\AdminReportController@getReportPracticeTeacher')->name('report_practice_teacher');
//Route::get('report/diploma', 'Admin\AdminReportController@getReportDiploma')->name('report_diploma');

// Информация и сроки
Route::get('admin/practice-info/{directionId}/{courseId?}', 'Admin\AdminPracticeController@index')->name('practice_info');
Route::post('admin/practice-info/{directionId?}/{courseId?}/edit', 'Admin\AdminPracticeController@edit')->name('practice_info_edit');

//Файлы
Route::get('admin/files/{directionId?}/{courseId?}', 'Admin\AdminFilesController@index')->name('files');

Route::post('admin/file/upload/{directionId}/{courseId}', 'Admin\AdminFilesController@upload')->name('file_upload');
Route::get('admin/file/download/{directionId}/{courseId}/{fileId}', 'Admin\AdminFilesController@download')->name('file_download');
Route::post('admin/file/delete/{directionId}/{courseId}/{fileId}', 'Admin\AdminFilesController@destroy')->name('file_delete');

Route::get('student/file/download/{fileId}', 'FilesController@studentDownload')->name('student_file_download');
Route::get('teacher/file/download/{directionId}/{courseId}/{fileId}', 'FilesController@teacherDownload')->name('teacher_file_download');
