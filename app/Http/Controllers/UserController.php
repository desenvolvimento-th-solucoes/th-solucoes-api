<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\ConfirmationMailer;
use Illuminate\Support\Facades\Mail;

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
                'password' => $request->input('password')
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
        ]);

        try {
            $user = User::find($request->input("id"));
            if ($user) {
                $updated = [];
                $nullable = ["name", "last_name", "email", "telephone"];

                foreach($nullable as $field){
                    if($request["data"][$field] !== null && $request["data"][$field] !== $user->$field) {
                        $updated[$field] = $request["data"][$field];
                    }
                }

                if($updated !== []) {
                    User::where("id", "=", $request["id"])->update($updated);
                    return response()->json([
                        "message" => "User successfully updated"
                    ]);
                } else {
                    return response()->json([
                        "message" => "Nothing to update"
                    ]);
                }
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

    public function redefine(Request $request) {
        $this->validate($request, [
            "id" => "required|numeric",
            "password" => "required|string|min:8"
        ]); 

        $user = User::where("id", "=", $request["id"])->first();

        if($user){
            if(!Hash::check($request["password"], $user->password)) {
                User::where("id", "=", $user->id)->update([
                    "password" => Hash::make($request["password"])
                ]);
                return response()->json([
                    "message" => "Password redefined successfully"
                ]);
            }        
            return response()->json([
                "message" => "The password is the same"
            ]);
        }
        return response()->json([
            "message" => "An error occurred on redefinition password process"
        ]);
    }
}
