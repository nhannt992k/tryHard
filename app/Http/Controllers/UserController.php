<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Illuminate\Support\Facades\Validator;

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
            return response()->json([], Response::HTTP_BAD_GATEWAY);
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
            return response()->json(["message" => "Can't take information this user"], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = Validator::make($request->all(), [
            "username" => "sometimes|nullable|string|max:255",
            "avatar" => "sometimes|nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048",
        ]);
        try {
            if ($validated->fails()) {
                return response()->json(["error" => "Cannot update"], Response::HTTP_BAD_REQUEST);
            }
            $fields = $validated->only("name", "avatar");
            $fileName  = $validated->file("avatar")->getClientOriginalName();
            $pathImage = $validated->file("avatar")->storeAs("uploads", $fileName, "public");
            $fields["avatar"] = $pathImage;
            $fields = array_filter($fields, fn ($value) => !is_null($value));
            $data = User::where("id", $user->id)->update($fields);
            return response()->json(["message" => "Update successful"], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
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
                // Còn thiếu bước kiểm tra đơn hàng có đang được vận chuyển hay mới mua gần đây!!!!!
                // lên lịch xoá sau 30 ngày nếu không đăng nhập lại! 
                return response()->json([
                    "status" => true,
                    "message" => "Your account is scheduled to be deleted in 30 days"
                ], Response::HTTP_ACCEPTED);
            }
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Can't delete your account! Sure you don't have books status delivery or don't have transaction least 7days",

            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
