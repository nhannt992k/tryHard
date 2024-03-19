<?php

namespace App\Http\Controllers;

use App\Customs\Services\EmailVerificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\EmailVerifiedNotification;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(private EmailVerificationService $emailVerificationService){

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    /* public function store(Request $request)
    {
        $validated = $request->validate([
            "email" => "sometimes|nullable|email",
            "username" => "sometimes|nullable|string",
            "phonenumber" => "sometimes|nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10",
        ]);
        try {
            if ($validated) {
                $request["password"] = Hash::make($request["password"]);
                $request["role_id"] = $request["role_id"] ?? 0;
                $user = User::create($request->all());
                // $this->emailVerificationService->sendVerificationLink($user);
                // $this->guard()->login($user);
                return response()->json(["message" => "Account created successfully"], Response::HTTP_OK);
            } else {
                return response()->json(["error" => "Cant create this use"], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        }
    }
 */
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $data = User::select("username", "phone_number", "email", "email_verified_at", "avatar")->find($user->id);
            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "username" => "required|nullable|string|max:255",
            "avatar" => "sometimes|nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048",
        ]);
        try {
            if ($validated) {
                $fields = $request->only("name", "avatar");
                $fileName  = $request->file("avatar")->getClientOriginalName();
                $pathImage = $request->file("avatar")->storeAs("uploads", $fileName, "public");
                $fields["avatar"] = $pathImage;
                $fields = array_filter($fields, fn ($value) => !is_null($value));
                $data = User::where("id", $user->id)->update($fields);
                return response()->json(["message" => "Update successful"], Response::HTTP_ACCEPTED);
            } else {
                return response()->json(["error" => "Cannot update"], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
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
                return response()->json(["message" => "Account deleted successfully"], Response::HTTP_OK);
            } else {
                return response()->json(["error" => "Account deletion unsuccessful"], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(["error" => "Undefined error"], Response::HTTP_BAD_REQUEST);
        }
    }
}
