<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use function PHPUnit\Framework\isNull;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Author::all();
            return response()->json([$data], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(["message" => "List of author now's empty"], Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                "name" => "required",
                "desriptiont" =>  "required"
            ]);
            $author = Author::create($validated->all());
            return response()->json(["message" => "Create new author sucessfull"], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(["message" => "We can't create this author"], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        try {
            $data = Author::join("books", "author_id", "authors.id")
                ->join("images", "images.book_id", "books.id")
                ->select("books.id", "books.price", "books.quantity", "images.image")
                ->where("images.is_default", 1)
                ->where("categories.id", $author->id)
                ->get();
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "We can't take books of this author for you right now!! Comeback soon!!!",
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        try {
            $validated = Validator::make($request->all(), [
                "name" => "required",
                "description" =>  "required",
            ]);
            if ($validated->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => "Make sure you write name or description for this author",
                ], Response::HTTP_UNAUTHORIZED);
            }
            $fields = $validated->only("name", "description");
            $fields = array_filter($fields, fn ($value) => !isNull($value));
            $data = Author::where("id", $author->id)
                ->update($validated->all());
            return response()->json([
                "status" => false,
                "message" => "Update author success"
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
