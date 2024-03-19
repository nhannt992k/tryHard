<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Cart;
use App\Models\User;
use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Validator;

class CartController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|integer',
            'amount' => 'required|integer',
        ]);
        if ($validated) {
            Cart::create($request->all());
            return response()->json(['success' => 'Thêm sản phẩm vào giỏ hàng thành công'], Response::HTTP_CREATED);
        } else {
            return response()->json(['error' => ''], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $data = Cart::query()
                ->join("users", "users.id", "=", "carts.user_id")
                ->join("books", "books.id", "=", "carts.book_id")
                ->join("images", "images.book_id", "=", "books.id")
                ->select('carts.id' . 'users.id', 'books.id', 'books.name', 'images.image', 'carts.amount')
                ->where('images.is_default', 1)
                ->where('carts.user_id', $user->id)
                ->get();
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        $validated  = $request->validate([
            'amount' => 'required|integer',
        ]);
        try {
            if ($validated) {
                $result = Cart::findOrFail('id', $cart->id);
                if ($result) {
                    $result->update($request->only('amount'));
                    return response()->json(['success' => 'Đã lưu các cập nhật của bạn'], Response::HTTP_ACCEPTED);
                } else {
                    return response()->json(['message' => 'Không thể cập nhật số lượng sản phẩm của bạn'], Response::HTTP_NOT_ACCEPTABLE);
                }
            } else {
                return response()->json(['message' => 'Có lỗi trong quá trình cập nhật'], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        try {
            $result = Cart::find($cart->id);
            if ($result) {
                $result->delete();
                return response()->json(['success' => 'Đã xoá khỏi giỏ hàng'], Response::HTTP_ACCEPTED);
            } else {
                return response()->json(['message' => 'Sản phẩm bạn chọn không tòn tại'], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
