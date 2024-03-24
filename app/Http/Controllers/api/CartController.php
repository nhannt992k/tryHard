<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                "book_id" => "required|integer",
                "amount" => "required|integer",
            ]);
            if ($validated->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => "Somethings wrong try again please",
                ], Response::HTTP_UNAUTHORIZED);
            }
            Cart::create($request->all());
            return response()->json([
                "status" => true,
                "success" => "Product has been added your cart"
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "We can't add this product to your cart",
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $data = Cart::query()
                ->join("users", "users.id", "carts.user_id")
                ->join("books", "books.id", "=", "carts.book_id")
                ->join("images", "images.book_id", "=", "books.id")
                ->select("carts.id" , "users.id", "books.id", "books.name", "images.image", "carts.amount")
                ->where("images.is_default", 1)
                ->where("carts.user_id", $user->id)
                ->get();
            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "We can't take your cart, try login again",
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /* public function update(Request $request, Cart $cart)
    {
        $validated  = Validator::make($request->all(), [
            "amount" => "required|integer",
        ]);
        try {
            if ($validated) {
                $result = Cart::findOrFail("id", $cart->id);
                if ($result) {
                    $result->update($request->only("amount"));
                    return response()->json(["success" => "Đã lưu các cập nhật của bạn"], Response::HTTP_ACCEPTED);
                } else {
                    return response()->json(["message" => "Không thể cập nhật số lượng sản phẩm của bạn"], Response::HTTP_NOT_ACCEPTABLE);
                }
            } else {
                return response()->json(["message" => "Có lỗi trong quá trình cập nhật"], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
 */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
       // Global hepler
        // Auth::user()-
        try {
            $result = Cart::find($cart->id);
            if ($result) {
                $result->delete();
                return response()->json([
                    "status" => true,
                    "message" => "Product have been remove from your cart",
                ], Response::HTTP_ACCEPTED);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "We can't find this product is sold out or not accept to buy",
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "We can't delete this cart",
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
