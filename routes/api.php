<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\ExpenseController;

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
Route::prefix('auth')->group(function () {

    Route::post('register', [AuthController::class, 'register']);

    Route::post('login', [AuthController::class, 'login']);

    Route::post('upload-image', [CategoryController::class, 'upload_image']);

    Route::post('change-password', [AuthController::class, 'changePassword']);
});

Route::middleware('auth:api')->group(function () {

    Route::prefix('auth')->group(function () {

        Route::get('logout', [AuthController::class, 'logout']);


        Route::post('change-password', [AuthController::class, 'resetpassword']);
    });

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

        Route::get('/user-categories', [CategoryController::class, 'user_categories']);

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

        Route::get('/user-sub-categories', [SubCategoryController::class, 'user_sub_categories']);

        Route::post('/store', [SubCategoryController::class, 'store'])->where(['id' => '[0-9]+']);

        Route::delete('/delete', [SubCategoryController::class, 'destroy'])->where(['id' => '[0-9]+']);
    });
    /*
|--------------------------------------------------------------------------
| API EXPENSE Routes
|--------------------------------------------------------------------------
*/

    Route::prefix('expense')->group(function () {

        Route::get('/all', [ExpenseController::class, 'index']);

        Route::post('/store', [ExpenseController::class, 'store'])->where(['id' => '[0-9]+']);

        Route::delete('/delete', [ExpenseController::class, 'destroy'])->where(['id' => '[0-9]+']);
    });
});
