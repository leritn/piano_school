<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Debug
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('config','ConfigController@index');
// Initialize permission
//Route::get('initializePermission', 'Initializer@initializePermissions');

Route::get('/',function () {
	return view('auth/login');
});

Entrust::routeNeedsPermission('student/create', 'create-student');
Entrust::routeNeedsPermission('student/*/edit', 'edit-student');

Entrust::routeNeedsPermission('teacher/create', 'create-teacher');
Entrust::routeNeedsPermission('teacher/*/edit', 'edit-teacher');

Entrust::routeNeedsPermission('schedule/create', 'create-schedule');
Entrust::routeNeedsPermission('schedule/*/edit', 'edit-schedule');
Entrust::routeNeedsPermission('schedule/confirm', 'confirm-taught-class');

Route::group(['middleware' => 'auth'] ,function()
{
	Route::get('/home', 'HomeController@dashboard');
	Route::post('/home', 'HomeController@dashboard');

	Route::post('student/restore','StudentController@restore');
	Route::get('student/deleted','StudentController@viewDeletedStudent');
	Route::resource('student', 'StudentController');

	Route::post('teacher/restore','TeacherController@restore');
	Route::get('teacher/deleted','TeacherController@viewDeletedTeacher');
	Route::resource('teacher','TeacherController');


	Route::post('schedule/confirm', 'ScheduleController@status');
	Route::resource('schedule', 'ScheduleController');

	Route::resource('payment', 'PaymentController');

	// Registration routes
	Route::get('auth/register', 'Auth\AuthController@getRegister');
	Route::post('auth/register', 'Auth\AuthController@postRegister');

	Route::get('calendar',function () {
		return view('calendar.calendar');
	});

	//Route::resource('newschedule','NewScheduleController');
	Route::get('teacherschedule','TeacherScheduleController@index');
	Route::post('teacherschedule','TeacherScheduleController@index');
});




// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');




/*Event::listen('illuminate.query', function($query)
{
    var_dump($query);
});*/

