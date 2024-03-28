<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClubResource;
use App\Models\Club;
use App\Models\User;
use App\Models\UsersClubs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::all();

        if ($clubs->isEmpty()) {
            return response([
                'message' => 'No club found'
            ], 200);
        }

        return ClubResource::collection($clubs)->additional(['message' => 'Retrieved clubs data']);
    }
    public function getUserClubs()
    {
        $user = User::find(Auth::id());

        return ClubResource::collection($user->clubs)->additional(['message' => "Retrieved user's clubs"]);
    }
}
