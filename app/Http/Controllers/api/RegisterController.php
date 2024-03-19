<?php

namespace App\Http\Controllers\api;

use App\Events\NewUserRegistered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Exception;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'name' => "required|string|max:30",
                'email' => "required|email|unique:users,email",
                'password' => "required|string",
            ]);
            if ($validated->fails()) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'validate error',
                    'errors' => $validated->errors()
                ], Response::HTTP_UNAUTHORIZED);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            // $user->notify(new VerifyEmail());
            $user->sendEmailVerificationNotification();
            return response()->json([
                'status' => 'success',
                'message' => 'User created',
                'token' => $user->createToken('api token')->plainTextToken
            ],Response::HTTP_CREATED );
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Create fail',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
