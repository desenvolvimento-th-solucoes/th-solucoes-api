<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends Controller
{
    public function login(Request $request){
        $this->validate($request, [
            "email" => "required|email",
            "password" => "required|min:8"
        ]);

        try{
            $credentials = $request->only("email", "password");
            if($token = JWTAuth::attempt($credentials)){
                return response()->json([
                    'token' => $token
                ], 200);
            }
            return response()->json([
                'error' => "Unauthorized"
            ], 201);
        } catch (QueryException $error) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 201);
        }
    }

    public function check(Request $request){
        if($request->hasCookie("token")){
            return response()-json([
                "message" => "Authenticated"
            ], 200);
        }
        return response()->json([
            "message" => "Not authenticated"
        ], 401);
    }
}
