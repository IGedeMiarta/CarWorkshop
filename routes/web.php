<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CarOwnerController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\CarRepairController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MechanicRepairController;
use App\Http\Controllers\OwnerRepairController;

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

Route::get('/', function () {
    return view('auth.login',['title'=>'Login']);
})->name('login');
Route::get('/register', function () {
    return view('auth.register',['title'=>'Register']);
})->name('register');
Route::post('/register',[AuthController::class,'regist'])->name('register.post');
Route::post('/login',[AuthController::class,'authenticate'])->name('login.post');


Route::group(['middleware' => ['auth','jwt.verify']], function (){
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');  
    Route::resource('owner', CarOwnerController::class);
    Route::resource('service', ServiceController::class);
    Route::resource('mechanic', MechanicController::class);
    Route::resource('status', StatusController::class);
    Route::resource('car-repair', CarRepairController::class);
    
    Route::resource('mechanic-repair', MechanicRepairController::class);
    Route::resource('owner-car', OwnerRepairController::class);
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
});