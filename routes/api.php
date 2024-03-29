<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MaterialController;

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
// Documentation 1. Authentication
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('clubs')->group(function () {

        // To get all the club
        // documentation 2. Club - Get all clubs
        Route::get('/', [ClubController::class, 'index']);

        // To get the specific club
        // documentation 2. Club - Get a club
        Route::get('/{clubId}', [ClubController::class, 'show']);

        // To create a club
        // documentation 2. Club - Create new club
        Route::post('/', [ClubController::class, 'store']);

        // To edit a club information - only mentor can do this
        // documentation 2. Club - Update a club
        Route::put('/{clubId}', [ClubController::class, 'update'])->middleware('isMentor');

        // To delete a club - only mentor can do this
        // documentation 2. Club - Delete a club
        Route::delete('/{clubId}', [ClubController::class, 'delete'])->middleware('isMentor');

        // To get club's members information - Only members of the club that can do this
        // documentation 3. Member - b. Get all club's member
        Route::get('/{clubId}/members', [MemberController::class, 'getMembers'])->middleware('isMember');

        // To remove club's member - Only Mentor can do it
        // Documentation 3. Member - c. remove member
        Route::delete('/{clubId}/members/{userId}', [MemberController::class, 'remove'])->middleware('isMentor');

        // To create a new material - Only mentor can do it
        // Documentation 4. Material - a. Create club material
        Route::post('/{clubId}/materials', [MaterialController::class, 'create'])->middleware('isMentor');

        // To create a new material - Only Member can do it
        // Documentation 4. Material - b. Get all club's material
        Route::get('/{clubId}/materials', [MaterialController::class, 'getClubMaterials'])->middleware('isMember');

        // To create a new material - Only Member can do it
        // Documentation 4. Material - c. Get a specific club's material
        Route::get('/{clubId}/materials/{materialId}', [MaterialController::class, 'getSpecificMaterial'])->middleware('isMember');

        // To create a new material - Only mentor can do it
        // Documentation 4. Material - d. Delete a club's material
        Route::delete('/{clubId}/materials/{materialId}', [MaterialController::class, 'removeMaterial'])->middleware('isMentor');
    });
    
    // to join a club - user can do this
    // documentation 3. Member - a. Join in a club
    Route::post('/join/{clubId}', [MemberController::class, 'joinClub']);
});
