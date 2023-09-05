<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function register(Request $request){
        $this->validate($request, [
            'name' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string',
            'password' => 'required|min:8'
        ]);

        try{    
            $user = User::create([
                'name' => $request->input('name'),
                'last_name' => $request->input('lastName'),
                'email' => $request->input('email'),
                'telephone' =>$request->input('telephone'),
                'password' => bcrypt($request->input('password'))
            ])->save();

            return response()->json([
                'message' => 'User successfully inserted into the database.',
                'code' => 201
            ]);
        } catch (QueryException $error) {
            return response()->json([
                'message' => 'An error occurred on insertion user into the database.',
                'error' => $error->getMessage(),
                'code' =>  $error->getCode()
            ]);
        }
    }

    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8'
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
}
