<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Cart;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\Response;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /* dd($request->all()); */
        try {
            if (!empty($request)) {
                $cartItems = Cart::select("user_id", "book_id", "amount")
                    ->whereIn("id", $request->id)
                    ->get()
                    ->toArray();
                $userId = $cartItems[0]['user_id'];
                /* dd($userId); */
                $invoice = Invoice::create(['total_amount' => $userId]);
                $invoiceItems = [];
                foreach ($cartItems as $item) {
                    $invoiceItems[] = new InvoiceItem($item);
                }
                $invoice->items()->saveMany($invoiceItems);
                Cart::whereIn('id', $request->id)->delete();
                return response()->json(['message' => 'Đã xuất hoá đơn thành công'], Response::HTTP_CREATED);
            } else {
                return response()->json(["message" => "Không thể tạo hoá đơn"], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
