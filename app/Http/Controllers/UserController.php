<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

use App\Models\User;
use Validator;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $request->validated();
            $request['password'] = Hash::make($request['password']);
            $request['role_id'] = $request['role_id'] ?? 0;
            $user = User::create($request->all());
            return response()->json(['message' => 'Account created successfully'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $data = User::select('username', 'phone_number', 'email', 'email_verified_at', 'avatar')->find($user->id);
            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $request->validated();
            $fields = $request->only('name', 'avatar');
            $fields = array_filter($fields, fn ($value) => !is_null($value));
            $data = User::where('id', $user->id)->update($fields);
            return response()->json(['message' => 'Update successful'], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $result = User::find($user->id);
            if ($result) {
                $result->delete();
                return response()->json(['message' => 'Account deleted successfully'], Response::HTTP_OK);
            } else {
                return response()->json(['error' => 'Account deletion unsuccessful'], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Undefined error'], Response::HTTP_BAD_REQUEST);
        }
    }
}
