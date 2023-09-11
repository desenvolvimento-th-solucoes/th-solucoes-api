<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;

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
            User::create([
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
}
