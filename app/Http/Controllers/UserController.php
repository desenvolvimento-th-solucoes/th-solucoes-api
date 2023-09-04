<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request){
        $name = $request->input("name");
        $last_name = $request->input("last_name");
        $email = $request->input("email");
        $telephone = $request->input("telephone");
        $password = $request->input("password");

        if($email && $last_name && $email && $telephone && $password) {
            return [];
        }
    }
}
