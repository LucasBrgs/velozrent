<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserController;

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

Route::post('/brands/create', [BrandController::class, 'store']);
Route::post('/models/create', [ModelController::class, 'store']);
Route::get('/cars', [CarController::class, 'index']);
Route::post('/cars/create', [CarController::class, 'store']);
Route::post('/photos/create', [PhotoController::class, 'store']);
Route::post('/rentals/create', [RentalController::class, 'store']);
Route::post('/users/create', [UserController::class, 'store']);
Route::get('/users/{user_id}/rentals', [UserController::class, 'getUserRentals']);
