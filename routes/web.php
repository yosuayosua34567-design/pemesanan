<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MechanicsController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\ExpertsController;
use App\Http\Controllers\WorksController;
use App\Http\Controllers\SurveysController;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('login');
});

Route::post('/login_admin', [CustomersController::class, 'admin']);
Route::get('/home_admin', [CustomersController::class, 'home_admin']);
Route::post('/loginPost', [CustomersController::class, 'loginpost']);
Route::get('/home', [CustomersController::class, 'home']);
Route::get('/home_mekanik', [CustomersController::class, 'home_mekanik']);
Route::get('/logout', [CustomersController::class, 'logout']);

Route::post('/works_detail_temp', [WorksController::class, 'works_detail_temp']);
Route::post('/mechanics_detail_temp', [MechanicsController::class, 'mechanics_detail_temp']);
Route::get('/schedule_admin', [SchedulesController::class, 'schedule']);
Route::post('/schedule_admin', [SchedulesController::class, 'schedule_admin']);
Route::get('/schedule_mekanik', [SchedulesController::class, 'schedule_mekanik']);
Route::get('/schedule_admin_result/{tanggal}', [SchedulesController::class, 'schedule_admin_result']);

Route::post('/surveys_detail_temp', [SurveysController::class, 'surveys_detail_temp']);

Route::get('/booking_admin', [BookingsController::class, 'index_admin']);
Route::get('/survey/create/{id_booking}', [SurveysController::class, 'create_']);
Route::get('/booking_batal/{id_booking}', [BookingsController::class, 'booking_batal']);
Route::get('/booking_reset/{id_booking}', [BookingsController::class, 'booking_reset']);


Route::resource('survey', SurveysController::class);
Route::resource('expert', ExpertsController::class);
Route::resource('mechanic', MechanicsController::class);
Route::resource('customer', CustomersController::class);
Route::resource('booking', BookingsController::class);
Route::resource('schedule', SchedulesController::class);
Route::resource('work', WorksController::class);
