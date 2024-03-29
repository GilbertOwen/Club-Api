<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use App\Models\UsersClubs;
use Illuminate\Http\Request;
use App\Http\Resources\ClubResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MemberResource;
use Illuminate\Support\Facades\Validator;

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
    public function show($clubId)
    {
        $club = Club::find($clubId);

        if (!$club) {
            return response([
                'message' => 'No club found'
            ], 404);
        }

        return (new ClubResource($club))->additional(['message' => 'Club successfully retrieved']);
    }
    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'name' => [
                'required',
                'unique:clubs,name'
            ],
            'description' => [
                'required',
            ],
            'tech_field' => [
                'required',
            ],
            'club_day' => [
                'required',
                'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'
            ]
        ]);

        if ($validateData->fails()) {
            return response([
                'message' => 'Invalid fields',
                'errors' => $validateData->errors()
            ]);
        }

        $data = $validateData->validated();

        $club = Club::create($data);

        if (!$club) {
            return response([
                'message' => 'Something went wrong, please try again'
            ]);
        }

        UsersClubs::create([
            'user_id' => Auth::id(),
            'club_id' => $club->id,
            'is_mentor' => true
        ]);

        return (new ClubResource($club))->additional(['message' => 'Club created successfully, now youre the mentor']);
    }
    public function update(Request $request, $clubId)
    {
        $club = Club::where('id', $clubId)->first();

        if (!$club) {
            return response([
                'message' => 'No club found'
            ], 404);
        }

        $rules = [
            'name' => [
                'min:3',
                "unique:clubs,name,$club->id"
            ],
            'description' => [
                '',
            ],
            'tech_field' => [
                'required',
            ],
            'club_day' => [
                '',
                'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'
            ]
        ];

        $validateData = Validator::make($request->all(), $rules);

        if (!$club->updateOrFail($validateData->validated())) {
            return response([
                'message' => 'Failed to update Club'
            ], 422);
        }

        return response([
            'message' => 'success',
            'club' => $club
        ], 200);
    }

    public function delete($clubId)
    {
        $club = Club::find($clubId);

        if (!$club) {
            return response([
                'message' => 'Club not found'
            ], 404);
        }

        if (!$club->delete()) {
            return response([
                'message' => 'Failed to delete club'
            ], 422);
        }

        return response([
            'message' => 'Successfully deleted club'
        ]);
    }
}
