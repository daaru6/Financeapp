<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\SubCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| API LOGIN & REGISTER Routes
|--------------------------------------------------------------------------
*/
Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::get('logout', [AuthController::class, 'logout']);

    Route::get('get-user-profile', [AuthController::class, 'get_user']);

/*
|--------------------------------------------------------------------------
| API ACCOUNTS Routes
|--------------------------------------------------------------------------
*/

    Route::prefix('account')->group(function () {

        Route::get('/get-user-accounts', [AccountController::class, 'index']);

        Route::post('/store', [AccountController::class, 'store']);

        Route::get('/get', [AccountController::class, 'show'])->where(['id' => '[0-9]+']);

        Route::delete('/delete', [AccountController::class, 'destroy'])->where(['id' => '[0-9]+']);

    });
/*
|--------------------------------------------------------------------------
| API CATEGORIES Routes
|--------------------------------------------------------------------------
*/    

    Route::prefix('category')->group(function () {

        Route::get('/all', [CategoryController::class, 'index']);

        Route::post('/store', [CategoryController::class, 'store'])->where(['id' => '[0-9]+']);

        Route::delete('/delete', [CategoryController::class, 'destroy'])->where(['id' => '[0-9]+']);

    });
/*
|--------------------------------------------------------------------------
| API CATEGORIES Routes
|--------------------------------------------------------------------------
*/     

    Route::prefix('sub-category')->group(function () {

        Route::get('/all', [SubCategoryController::class, 'index']);

        Route::post('/store', [SubCategoryController::class, 'store'])->where(['id' => '[0-9]+']);

        Route::delete('/delete', [SubCategoryController::class, 'destroy'])->where(['id' => '[0-9]+']);

    });
});
