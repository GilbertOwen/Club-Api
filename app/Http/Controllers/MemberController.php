<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\UsersClubs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function getMembers($clubId)
    {
        $clubMembers = UsersClubs::where('club_id', $clubId)->get();

        $club = Club::find($clubId);

        if (!$club) {
            return response([
                'message' => 'Club not Found'
            ], 404);
        }

        $members = [];

        foreach ($clubMembers as $member) {
            $members[] = [
                'name' => $member->user->name,
                'email' => $member->user->email,
                'is_mentor' => boolval($member->is_mentor)
            ];
        }

        return response([
            'club' => $club,
            'members' => $members,
            'message' => 'Retrieved club members'
        ], 200);
    }
    public function joinClub(Request $request, $clubId)
    {
        $user = Auth::user();
        $validate = Validator::make($request->all(), [
            'is_mentor' => 'boolean'
        ]);

        if ($validate->fails()) {
            return response([
                'message' => 'Invalid fields',
                'errors' => $validate->errors()
            ], 422);
        }

        if (!Club::where('id', $clubId)->exists()) {
            return response([
                'message' => 'Club not found'
            ], 404);
        }

        if (UsersClubs::where('user_id', $user->id)->where('club_id', $clubId)->exists()) {
            return response([
                'message' => "You already on the club"
            ], 403);
        }

        $data = $validate->validated();

        UsersClubs::create([
            'user_id' => $user->id,
            'club_id' => $clubId,
            'is_mentor' => $data['is_mentor']
        ]);

        return response([
            "message" => "Join Success"
        ]);
    }
    public function remove($clubId, $userId)
    {
        if (!Club::where('id', $clubId)->exists()) {
            return response([
                'message' => 'Club not found'
            ], 404);
        }
        $theUser = UsersClubs::where('user_id', $userId)->where('club_id', $clubId)->first();

        if (!$theUser) {
            return response([
                'message' => "No Member Found or the member is not in this club"
            ], 403);
        }

        $removed = $theUser->delete();

        if (!$removed) {
            return response([
                'message' => "Failed to remove the member"
            ]);
        }
        return response([
            'message' => "Removed successfully"
        ]);
    }
}
