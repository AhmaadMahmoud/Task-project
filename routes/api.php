<?php

use App\Http\Controllers\api\AccessTokenController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\UserController;
use App\Jobs\ForceDeleteOldPosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;





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

Route::post('register',[AccessTokenController::class, 'register']);
Route::post('auth/access-token',[AccessTokenController::class, 'store']); // login

Route::middleware(['auth:sanctum'])->group(function(){
    Route::delete('auth/access-token/{token?}',[AccessTokenController::class, 'logout']);
    Route::apiResource('tags',TagController::class);
    Route::apiResource('posts',PostController::class);
    Route::post('/verify-code', [AccessTokenController::class, 'verifyCode']);
});


Route::get('trash',[PostController::class, 'trashed']);
Route::post('/posts/restore/{post}', [PostController::class, 'restore']);
Route::get('stats',[StatsController::class, 'index']);

