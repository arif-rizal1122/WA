<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\MessageController;


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
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout',  [AuthController::class, 'logout']);
    Route::post('refresh',  [AuthController::class, 'refresh']);
    Route::post('me',  [AuthController::class, 'me']);
    Route::post('update',  [AuthController::class, 'update']);

    Route::post('/status/create', [StatusController::class, 'create']);
    Route::post('/status/all', [StatusController::class, 'getAll']);
    Route::get('/status/{id}', [StatusController::class, 'get'])->where('id', '[0-9]+');
    Route::post('/status/{id}', [StatusController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/status/{id}', [StatusController::class, 'delete'])->where('id', '[0-9]+');
    Route::get('/status/search', [StatusController::class, 'search']);


    Route::post('/status/{idstatus}/comment', [CommentController::class, 'create'])->where('idstatus', '[0+9]');
    Route::post('/status/{idstatus}/comment/{idcomment}', [CommentController::class, 'update'])->where(['idstatus' => '[0-9]+', 'idcomment' => '[0-9]+']);
    Route::delete('/status/{idstatus}/comment/{idcomment}', [CommentController::class, 'delete'])->where(['idstatus' => '[0-9]+', 'idcomment' => '[0-9]+']);
    Route::post('/user/comments', [CommentController::class, 'riwayatComment']);

});

