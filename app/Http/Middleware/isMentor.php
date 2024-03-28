<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Club;
use App\Models\UsersClubs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isMentor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!Club::find($request->id)->exists()) {
            return response([
                'message' => 'Club not found'
            ], 404);
        }

        $theUser = UsersClubs::where('club_id', $request->id)->where('user_id', $user->id)->first();

        if (!$theUser->is_mentor) {
            return response([
                'message' => "You're not a mentor"
            ], 401);
        }

        return $next($request);
    }
}
