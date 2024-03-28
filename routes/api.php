<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\MemberController;

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

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
Route::prefix('clubs')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ClubController::class, 'index']);
    Route::get('/{id}', [ClubController::class, 'show']);
    Route::post('/', [ClubController::class, 'store']);
    Route::put('/{id}', [ClubController::class, 'update'])->middleware('isMentor');
    Route::delete('/{id}', [ClubController::class, 'delete'])->middleware('isMentor');
    Route::get('/getUserClubs', [ClubController::class, 'getUserClubs']);
    Route::get('/{id}/members', [MemberController::class, 'getMembers']);
});
