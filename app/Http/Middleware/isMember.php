<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Club;
use App\Models\UsersClubs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!Club::find($request->clubId)) {
            return response([
                'message' => 'Club not found'
            ], 404);
        }

        if (!UsersClubs::where('user_id', $user->id)->where('club_id', $request->clubId)->exists()) {
            return response([
                'message' => "You're not in the club"
            ], 403);
        }

        return $next($request);
    }
}
