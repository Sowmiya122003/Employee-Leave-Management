<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('', function () {
    return view('auth.login');
});
Route::get('/login',[AuthController::class,'login'])->name('login');
Route::get('/register',[AuthController::class,'register'])->name('register');
// Route:get()
Route::post('/registersubmit',[AuthController::class,'registersubmit'])->name('register.submit');
