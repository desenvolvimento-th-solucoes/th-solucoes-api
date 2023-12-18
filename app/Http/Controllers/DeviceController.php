<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
                    "client_email" => $device->user["email"], 
                    "device" => $device["device"],
                    "observations" => $device["observations"] ?? "Nenhuma",
                    "password" => $device["password"],
                    "ip" => $device["ip"],
                    "updated_at" => $device["updated_at"],
                ];
            }

            return response()->json($output);
        } catch (Exception $error) {
            return response()->json([
                "error" => $error->getMessage()
            ]);
        }
    }

    public function record(Request $request) {
        $this->validate($request, [
            "email" => "required|email",
            "password" => "required|string",
            "device" => "required|string",
            "ip" => "required|string",
            "observation" => "nullable|string"
        ]);
        
        try {
            $existUserId = User::where("email", "=", $request["email"])->pluck("id")->first();
            if($existUserId) {
                $createdDevice = Device::create([
                    "user_id" => $existUserId,
                    "device" => $request["device"],
                    "observations" => $request["observations"],
                    "ip" => $request["ip"],
                    "password" => encrypt($request["password"])
                ]);
    
                return $createdDevice;
            }

            return response()->json([
                "error" => "Cannot find user with email " . $request->input("email")
            ]);
        } catch (Exception $error) {
            return response()->json([
                "error" => $error->getMessage()
            ]);     
        }
    }

    public function showPassword(Request $request) {
        $this->validate($request, [
            "operatorId" => "required",
            "deviceId" => "required",
            "password" => "required|string"
        ]);

        try {
            $existUserId = User::select()
            ->where("id", "=", $request->input('operatorId'))
            ->first();
            if ($existUserId && Hash::check($request->input('password'), $existUserId->password)) {
                $deviceFromDatabase = Device::where("id", "=", $request->input("deviceId"))->first();
                if ($deviceFromDatabase) {
                    return response()->json([
                        "device_id" => $deviceFromDatabase->id,
                        "password" => decrypt($deviceFromDatabase->password)
                    ]);
                }
                return response()->json([
                    "error" => "Cannot find device with ID ". $request->input("deviceId")
                ], 403);
            }
            return response()->json([
                "error" => "Wrong credential"
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                "error" => $error->getMessage()
            ]);
        }
    }
}
