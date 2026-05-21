<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('', function () {
    return view('auth.login');
});
Route::middleware('auth')->group(function(){
    Route::get('/dashboard',function(){return view('admin.dashboard');})->name('admin.dashboard');
    Route::get('/add-user',function(){return view('admin.add_employee');})->name('admin.add.employee');
    Route::post('/add-user',[UserController::class,'addUser'])->name('add.employee.data');
    Route::get('/employees',[UserController::class,'employee'])->name('employee-list');
    Route::get('/logout',[AuthController::class,'logout'])->name('logout');
});
Route::get('/login',[AuthController::class,'login'])->name('login');
Route::get('/register',[AuthController::class,'register'])->name('register');
// Route:get()
Route::post('/registersubmit',[AuthController::class,'registersubmit'])->name('register.submit');
Route::post('/loginsubmit',[AuthController::class,'loginsubmit'])->name('login.submit');
Route::get('/password/set/{token}',[UserController::class,'passwordset'])->name('password.set');
Route::post('/password-update',[UserController::class,'passwordupdate'])->name('password.update');
