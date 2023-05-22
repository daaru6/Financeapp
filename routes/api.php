<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AccountController;

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

Route::middleware('auth:api')->middleware('checkAccessToken')->group(function () {

    Route::get('logout', [AuthController::class, 'logout']);

    Route::get('get-user-profile', [AuthController::class, 'get_user']);

/*
|--------------------------------------------------------------------------
| API ACCOUNTS Routes
|--------------------------------------------------------------------------
*/

    Route::prefix('accounts')->group(function () {

        Route::get('/get-user-accounts', [AccountController::class, 'index']);

        Route::post('/store', [AccountController::class, 'store']);

        Route::get('/get', [AccountController::class, 'show'])->where(['id' => '[0-9]+']);

        // Route::put('/edit', [AccountController::class, 'store'])->where(['id' => '[0-9]+']);

        Route::delete('/delete', [AccountController::class, 'destroy'])->where(['id' => '[0-9]+']);

    });
});
