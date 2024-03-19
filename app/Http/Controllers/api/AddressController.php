<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        try {
            $data = Address::query()
                ->select("id", "province", "district", "ward", "commue", "address", "is_default")
                ->where("user_id", $user->id)
                ->get();
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        try {
            $validated = Validator::make($request->all(), [
                "province" => "required|string",
                "district" => "required|string",
                "ward" => "required|string",
                "commue" => "required|string",
                "address" => "required|string",
                "is_default" => "required|boolean",
            ]);
            if ($validated->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => "Check address again please make sure don't have fields empty ",
                    "errors" => $validated->errors()
                ], Response::HTTP_UNAUTHORIZED);
            }
            $data = Address::query()->create($request->all());
            return response()->json([
                "status" => true,
                "message" => "Add address sucess"
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Address can't create",
                "error" => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        try {
            $data = Address::find($address->id);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {
        try {
            // $request->validated();
            $validated = Validator::make($request->all(), [
                "province" => "required|string",
                "district" => "required|string",
                "ward" => "required|string",
                "commue" => "required|string",
                "address" => "required|string",
                "is_default" => "required|boolean"
            ]);
            if ($validated->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => "Some fields is empty or wrong ",
                    "error" => $validated->errors()
                ], Response::HTTP_UNAUTHORIZED);
            }
            $fields = $request->only("province", "district", "ward", "commue", "address", "is_default");
            $fields = array_filter($fields, fn ($value) => !isNull($value));
            $data = Address::where("id", $address->id)->update($fields);
            return response()->json([
                "status" => true,
                "message" => "Your address has been updated"
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                "status" => true,
                "message" => "Update address fail", "error" => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Address $address)
    {
        try {
            $data = Address::where("id", $address->id)
                ->where("user_id", $user->id)
                ->delete(); // Còn thiếu bước kiểm tra đơn hàng có đang được vận chuyển hay mới mua gần đây!!!!!
                // lên lịch xoá sau 30 ngày nếu không đăng nhập lại! 
            return response()->json([
                "status" => true,
                "message" => "Your account is scheduled to be deleted in 30 days"
            ], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Can't delete your account! Sure you don't have books status delivery or don't have transaction least 7days",
                "error" => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
