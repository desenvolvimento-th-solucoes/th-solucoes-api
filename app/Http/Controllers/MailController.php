<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMailer;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function contact(Request $request){
        $this->validate($request, [
            "name" => "required|string",
            "email" => "required|email",
            "body" => "required|string"
        ]);

        $contact_email = new ContactMailer($request->input("name"), $request->input("email"), $request->input("body"));
        Mail::to("ticket@thsolucoes.com")->send($contact_email);    
    }
}
