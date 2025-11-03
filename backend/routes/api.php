<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\ProjectRequestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//Users
Route::apiResource('/users', UserController::class)->middleware('auth:sanctum');

//Projects
Route::apiResource('/projects', ProjectController::class)->middleware('auth:sanctum');

//Project members
Route::apiResource('/project_members', ProjectMemberController::class)->middleware('auth:sanctum');

//Project requests
Route::apiResource('/project_requests', ProjectRequestController::class)->middleware('auth:sanctum');

//Project comments
Route::apiResource('/comments', CommentController::class)->middleware('auth:sanctum');

//Categories
Route::apiResource('/categories', CategoryController::class)->middleware('auth:sanctum');

Route::get('/', function () {
    return ['message' => 'api'];
});