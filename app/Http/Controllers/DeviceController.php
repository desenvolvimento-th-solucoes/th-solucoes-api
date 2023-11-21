<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\User;

class DeviceController extends Controller
{
    public function recoverAllDevices(Request $request) {
        try {
            $devices = Device::all();
            $output = [];

            foreach($devices as $device) {
                $output[] = [
                    "id" => $device["id"],
                    "client_name" => $device->user["name"], 
                    "device" => $device["device"],
                    "observations" => $device["observations"] ?? "Nenhuma",
                    "password" => $device["password"],
                    "updated_at" => $device["updated_at"],
                ];
            }

            return response()->json($output);
        } catch (Exception $error) {
            return response()->json([
                "error" => $error
            ]);
        }
    }

    public function assign(Request $request) {
        $this->validate($request, [
            "email" => "required|email",
            "password" => "required|string",
            "device" => "required|string",
            "ip" => "required|string",
            "observation" => "nullable|string"
        ]);
        
        try {
            $existUserId = User::where("email", $request["email"])->pluck("id")->first();
            
            if($existUserId) {
                $createdDevice = Device::create([
                    "user_id" => $existUserId,
                    "device" => $request["device"],
                    "observation" => $request["observation"],
                    "ip" => $request["ip"],
                    "password" => encrypt($request["password"])
                ]);
    
                return $createdDevice;
            }

            return response()->json([
                "error" => "Cannot find user with ID " . $existUserId
            ]);
        } catch (Exception $error) {
            return response()->json([
                "error" => $error
            ]);     
        }
    }

    public function showPassword(Request $request) {
        $this->validate($request, [
            "id" => "required|number",
            "password" => "required|string"
        ]);

        try {
            $existUserId = User::where("id", "=", $request->input('id'));
            if ($existUserId && Hash::check($request->input('password'), $existUserId["password"])) {
                return response()->json([
                    "device" => $existUserId->device,
                    "password" => decrypt($existUserId->device["password"])
                ]);
            }
            return response()->json([
                "error" => "Wrong credential "
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                "error" => $error
            ]);
        }
    }
}
