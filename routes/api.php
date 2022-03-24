<?php

use App\Models\CarOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CarOwnerController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\CarRepairController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/register', [AuthController::class, 'regist']);

Route::post('/logout',[AuthController::class,'logout'])->name('logout');

Route::get('all-owner',[ApiController::class,'getOwner']);
Route::resource('owner', CarOwnerController::class);
Route::get('all-mechanic',[ApiController::class,'getMechanic']);
Route::resource('mechanic', MechanicController::class);
Route::get('all-service',[ApiController::class,'getService']);
Route::resource('service', ServiceController::class);
Route::get('all-status',[ApiController::class,'getStatus']);
Route::resource('status', StatusController::class);

Route::get('all-repair',[ApiController::class,'getCarRepair']);
Route::resource('car-repair', CarRepairController::class);