<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::select("id", "name")->get();
            return response()->json($categories);
        } catch (Exception $e) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            $data = Category::join("books", "category_id", "=", "categories.id")
                ->join("images", "images.book_id", "=", "books.id")
                ->select("books.id", "books.price", "books.quantity", "images.image")
                ->where("images.is_default", 1)
                ->where("categories.id", $category->id)
                ->get();
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "We can't take books of this category for you right now!! Comeback soon!!!",
                
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {

            $validated = Validator::make($request->all(), [
                "name" => "required",
            ]);
            if ($validated->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => "Make sure you write name of category",
                    "error" => $validated->errors()
                ], Response::HTTP_UNAUTHORIZED);
            }
            $data = Category::where("id", $category->id)
                ->update($request->only("name"));
            return response()->json([
                "status" => false,
                "message" => "Update name category success"
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Update fail please try again",
                
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
