<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\LoginNotification;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                "email" => "required|email",
                "password" => "required|string"
            ]);

            if ($validated->fails()) {
                return response()->json([
                    "status" => "error",
                    "message" => "validate error",
                    "error" => $validated->errors()
                ], Response::HTTP_UNAUTHORIZED);
            }
            if (!Auth::attempt(request()->only(["email","password"]))) {
                return response()->json([
                    "status"=> "error",
                    "message"=> "Email and password wrong!",
                ], Response::HTTP_UNAUTHORIZED);
            }
            $user = User::where("email", $request->email)->first();
            $user->notify(new LoginNotification());
            return response()->json([
                "status"=> "success",
                "message"=> "Login Sucess",
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Create fail',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}


