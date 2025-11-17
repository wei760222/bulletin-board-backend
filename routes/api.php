<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NoticeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileController;

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

// 公開路由
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// 需要認證的路由
Route::middleware('auth:api')->group(function () {
    // 用戶相關
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // 公告相關
    Route::apiResource('notices', NoticeController::class);
    
    // 文件上傳
    Route::post('/upload', [FileController::class, 'upload']);
    Route::delete('/files/{id}', [FileController::class, 'destroy']);
});
