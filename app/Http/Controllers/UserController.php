<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

    public function save(Request $request){
        $this->validate($request, [
            "id" => "numeric|required",
            "name" => "string|nullable",
            "last_name" => "string|nullable",
            "email" => "string|nullable",
            "telephone" => "string|nullable",
            "password" => "string|nullable",
        ]);

        try {
            $user = User::find($request->input("id"));
            if ($user) {
                $updated = [];
                $nullable = ["id", "name", "last_name", "email", "telephone"];

                if($user["password"] !== $request->input("password")) {
                    $updated["password"] = Hash::make($request->input("password"));
                }

                foreach($nullable as $field){
                    if($user[$field] !== $request->input($field) && $request->input($field) !== null){                       
                        $updated[$field] = $request->input($field);
                    }
                }

                User::where("id", "=", $user->id)->update($updated);

                return response()->json([
                    "message" => "User successfully updated"
                ]);
            }
            return response()->json([
                "message" => "User not found."
            ]);
        } catch (\Exception $error) {
            return response()->json([
                "message" => $error->getMessage()
            ]);
        }
    }
}
