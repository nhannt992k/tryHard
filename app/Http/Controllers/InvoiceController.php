<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
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
    public function store(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                "user_id" => "required",
                "book_id" => "required",
                "amount" => "required",

            ]);
            if ($validated->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => "validate error",
                ], Response::HTTP_BAD_REQUEST);
            }
            $cartItems = Cart::select("user_id", "book_id", "amount")
                ->whereIn("id", $request->id)
                ->get()
                ->toArray();
            $userId = $cartItems[0]["user_id"];
            $invoice = Invoice::create(["total_amount" => $userId]);
            $invoiceItems = [];
            foreach ($cartItems as $item) {
                $invoiceItems[] = new InvoiceItem($item);
            }
            $invoice->items()->saveMany($invoiceItems);
            Cart::whereIn("id", $request->id)->delete();
            return response()->json([
                "status" => true,
                "message" => " Invoice has been exported, thanks you",
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Can't export your invoice",
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        try {
            $data = Invoice::join("invoice_items","invoice_id","=","invoice.id")
            ->join("users","users.id","=","user_id")
            ->join("books","books.id","=","book_id")
            ->select("invoice.id","books.name","invoice.create_at","invoice_items.amount")
            ->where("invoice.id", $invoice->id)
            ->ddRawSql()
            ->get();


        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /* public function update(Request $request, string $id)
    {
    } */

    /**
     * Remove the specified resource from storage.
     */
    /* public function destroy(string $id)
    {
    } */
}
