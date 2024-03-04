<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GeneralDashboradController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthorController;

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

Route::group(['prefix' => 'auth'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::get('logged', 'authenticated')->name('login');
        Route::post('logout', 'logout')->middleware('auth:sanctum');
    });
});

Route::group(['prefix' => 'general', 'middleware' => ['auth:sanctum']], function () {
    Route::controller(GeneralDashboradController::class)->group(function () {
        Route::post('card-counters', 'card_counters');
        Route::post('user-gender', 'user_gender');
        Route::post('user-registered-from', 'user_registered_from');
        Route::post('user-age-groups', 'user_age_groups');
        Route::post('viewers-age-groups', 'viewers_age_groups');
        Route::post('latest-posts', 'latest_posts');
    });
});

Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum']], function () {

    Route::controller(UserController::class)->group(function () {

        Route::post('userlist', 'index');

    });
});


Route::group(['prefix' => 'author', 'middleware' => []], function () {

    Route::controller(AuthorController::class)->group(function () {

        Route::post('authorlist', 'index');

    });
});