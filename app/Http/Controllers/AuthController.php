<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $validateData = Validator::make($req->all(), [
            'name' => [
                'required',
                'unique:users,name'
            ],
            'email' => [
                'unique:users,email',
                'required'
            ],
            'password' => [
                'required',
                'min:8'
            ]
        ]);

        if ($validateData->fails()) {
            return response([
                'message' => 'Invalid fields',
                'errors' => $validateData->errors()
            ], 422);
        }

        $user = User::create($validateData->validated());

        if (!$user) {
            return response([
                'message' => 'Something went wrong with the database, please try again',
            ], 422);
        }

        return response([
            'message' => 'Successfully created a new user',
        ], 201);
    }
    public function login(Request $req){
        $validateData = Validator::make($req->all(),[
            'email' => [
                'required',
            ],
            'password' => [
                'required',
                'min:8'
            ]
        ]);

        if($validateData->fails()){
            return response([
                'message' => 'Invalid fields',
                'errors' => $validateData->errors()
            ], 422);
        }

        if(!Auth::attempt($validateData->validated())){
            return response([
                'message' => 'Wrong email and password'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('sanctum')->plainTextToken;

        return response([
            'message' => 'Login success',
            'user'=> $user,
            'token' => $token
        ], 200);
    }
    public function logout(Request $request){
        $user = Auth::user();

        $user->tokens()->delete();

        $request->user()->currentAccessToken()->delete();
        
        return response([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
