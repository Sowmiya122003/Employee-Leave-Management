<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CompanyHolidayController;
use App\Http\Controllers\LeaveController;
use Illuminate\Support\Facades\Route;

Route::get('', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/add-user', [UserController::class, 'employeeForm'])->name('add.employee');
        Route::post('/add-user', [UserController::class, 'addEmployee'])->name('add.employee.data');
        Route::get('/admin-view-employee/{id}', [UserController::class, 'viewEmployee'])->name('view.employee');
        Route::get('/edit-employee/{id}', [UserController::class, 'editEmployee'])->name('edit.employee');
        Route::post('/update-employee/{id}', [UserController::class, 'updateEmployee'])->name('update.employee');
        Route::get('/delete-employee/{id}', [UserController::class, 'deleteEmployee'])->name('delete.employee');
        Route::get('/holidayform', [CompanyHolidayController::class, 'holidayForm'])->name('holidayform');
        Route::post('/holiday', [CompanyHolidayController::class, 'holidayCreate'])->name('add.company.holiday');
        Route::get('/send-holiday-pdf', [CompanyHolidayController::class, 'sendHolidayPdf'])->name('send.holiday.pdf');
        Route::get('/teams', [TeamController::class, 'showTeam'])->name('team.list');
        Route::get('/team-create', [TeamController::class, 'createTeam'])->name('team.create.form');
        Route::post('/team-create', [TeamController::class, 'teamSubmit'])->name('team.create');
        Route::get('/team-list', [TeamController::class, 'teamList'])->name('team-list');
        Route::get('/leave-type-form', [LeaveController::class, 'leaveTypeForm'])->name('leave.type.form');
        Route::post('/leave-type-create', [LeaveController::class, 'leaveTypeCreate'])->name('leave.type.create');
    });

    Route::prefix('manager')->name('manager.')->group(function () {
        Route::get('/employees', [UserController::class, 'employee'])->name('employee-list');
        Route::get('/holiday-list', [CompanyHolidayController::class, 'holidayList'])->name('holiday.list');
        Route::post('/approved/{id}', [LeaveController::class, 'requestApproved'])->name('leave.approved');
        Route::post('/rejected/{id}', [LeaveController::class, 'requestRejected'])->name('leave.rejected');
    });

    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/view-profile/{id}', [UserController::class, 'viewProfile'])->name('profile');
        Route::get('/leave-type', [LeaveController::class, 'leaveType'])->name('leave.type');
        Route::get('/leave-request', [LeaveController::class, 'leaveRequestForm'])->name('leave.request');
        Route::post('/create-leave-request', [LeaveController::class, 'createLeaveRequest'])->name('create.leave');
        Route::get('/leave-requests', [LeaveController::class, 'leaveRequest'])->name('leave.requests');
        Route::get('/request-cancel/{id}', [LeaveController::class, 'requestCancel'])->name('leave.cancel');
        Route::get('/leave-balance', [LeaveBalanceController::class, 'leaveBalance'])->name('leave.balance');
    });
});

Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/registersubmit', [AuthController::class, 'registerSubmit'])->name('register.submit');
    Route::post('/loginsubmit', [AuthController::class, 'loginSubmit'])->name('login.submit');
});

Route::get('/password/set/{token}', [UserController::class, 'passwordSet'])->name('password.set');
Route::post('/password-update', [UserController::class, 'passwordUpdate'])->name('password.update');
