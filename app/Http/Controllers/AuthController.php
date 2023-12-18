<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTFactory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $credentials = $request->only("email", "password");
        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            if($token = JWTAuth::attempt($credentials)){
                $cookie = cookie('token', $token, 60)
                    ->withPath('/')
                    ->withSecure(true) 
                    ->withHttpOnly(true);

                $response = response()->json([
                    'message' => 'Authentication has been successful.'
                ], 201)->withCookie($cookie);

                $response->headers->setCookie($cookie->withSameSite('None'));

                return $response;
            }
            return response()->json([
                'error' => "Authentication Failure."
            ], 401);
        }
        return response()->json([
            'error' => "Authentication Failure."
        ], 401);
    }

    public function checkToken(Request $request) {
        $token = $request->cookie("token");
    
        if ($token) {
            try {
                JWTAuth::setToken($token);
                $user = JWTAuth::parseToken()->authenticate();
    
                if ($user) {
                    return response()->json([
                        "message" => "Authenticated",
                        "logged_user" => [
                            "id" => $user->id,
                            "name" => $user->name,
                            "last_name" => $user->last_name,
                            "email" => $user->email,
                            "telephone" => $user->telephone
                        ]
                    ], 200);
                }
    
                return response()->json([
                    "message" => "Not authenticated"
                ], 401);
    
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json([
                    "message" => "Token expired"
                ], 401);
            } catch (\Exception $error) {
                return response()->json([
                    "message" => "Error on generating process of a Json Web Token (JWT)"
                ], 401);
            }
        }
    
        return response()->json([
            "message" => "Not authenticated"
        ], 401);
    }

    public function destroyToken(Request $request){
        if($request->cookie("token")) {
            $cookie = Cookie::forget("token");
            return response()->json([
                "message" => "The session was finished."
            ], 200)->withCookie($cookie);
        }
        return response()->json([
            "message" => "Error when finish the session"
        ], 401);
    }
}
