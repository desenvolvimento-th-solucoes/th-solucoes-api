<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $credentials = $request->only("email", "password");
        if($token = JWTAuth::attempt($credentials)){
            $cookie = Cookie::make("token", $token, 60);

            return response()
            ->json([
                'message' => 'Authentication has been successful.'
            ], 201)
            ->withCookie($cookie);
        }

        return response()->json([
            'error' => "Authentication Failure."
        ], 401);
    }

    public function check(Request $request) {
        if($request->cookie("token")){
            return response()->json([
                "message" => "Authenticated"
            ], 201);
        }
        return response()->json([
            "message" => "Not authenticated"
        ], 200);
    }
}
