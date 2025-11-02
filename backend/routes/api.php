<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
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
Route::apiResource('/projectMembers', ProjectMemberController::class)->middleware('auth:sanctum');

Route::get('/', function () {
    return ['message' => 'api'];
});