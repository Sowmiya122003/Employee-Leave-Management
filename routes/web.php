<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CompanyHolidayController;
use App\Http\Controllers\LeaveController;
use App\Models\CompanyHoliday;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Support\Facades\Route;

Route::get('', function () {
    return view('auth.login');
});
Route::middleware('auth')->group(function(){
    Route::get('/dashboard',function(){
        $holidays = CompanyHoliday::select('title','holiday_date')->get();
        return view('admin.dashboard',compact('holidays'));})->name('admin.dashboard');
    Route::get('/add-user',[UserController::class,'employeeForm'])->name('admin.add.employee');
    Route::post('/add-user',[UserController::class,'addEmployee'])->name('add.employee.data');
    Route::get('/employees',[UserController::class,'employee'])->name('employee-list');
    Route::get('/logout',[AuthController::class,'logout'])->name('logout');
    Route::get('holiday-list',[CompanyHolidayController::class,'holidaylist'])->name('holiday.list');
    Route::get('/holidayform',[CompanyHolidayController::class, 'holidayform'])->name('holidayform');
    Route::post('/holiday',[CompanyHolidayController::class, 'holidaycreate'])->name('add.company.holiday');
    Route::get('/teams',[TeamController::class,'showteam'])->name('team.list');
    Route::get('/team-create',[TeamController::class,'createteam'])->name('team.create.form');
    Route::post('/team-create',[TeamController::class,'teamsubmit'])->name('team.create');
    Route::get('/send-holiday-pdf',[CompanyHolidayController::class,'sendHolidayPdf'])->name('send.holiday.pdf');
    Route::get('leave-type',[LeaveController::class,'leaveType'])->name('leave.type');
    Route::get('/leave-type-form',[LeaveController::class,'leaveTypeForm'])->name('leave.type.form');
    Route::post('/leave-type-create',[LeaveController::class,'leaveTypeCreate'])->name('leave.type.create');
});
Route::get('/login',[AuthController::class,'login'])->name('login');
Route::get('/register',[AuthController::class,'register'])->name('register');
// Route:get()
Route::post('/registersubmit',[AuthController::class,'registersubmit'])->name('register.submit');
Route::post('/loginsubmit',[AuthController::class,'loginsubmit'])->name('login.submit');
Route::get('/password/set/{token}',[UserController::class,'passwordset'])->name('password.set');
Route::post('/password-update',[UserController::class,'passwordupdate'])->name('password.update');

