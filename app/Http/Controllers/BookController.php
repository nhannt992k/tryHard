<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Image;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Book::query()
                ->join('images', 'images.book_id', '=', 'books.id')
                ->select('books.id', 'books.price', 'books.quantity', 'images.image')
                ->where('images.is_default', 1)->get();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Book $book)
    {
        try {
            $data = Book::query()
                ->join('authors', 'authors.id', '=', 'books.author_id')
                ->join('publishers', 'publishers.id', '=', 'books.publisher_id')
                ->select('books.id', 'books.name', 'books.quantity', 'books.price', 'authors.name', 'publishers.name')
                ->where('books.id', $book->id)
                ->first();

            if ($data) {
                $images = Image::where('book_id', $data->id)->pluck('image')->toArray();
                $data = $data->toArray();
                $data['images'] = $images;
                return response()->json($data);
            } else {
                return response()->json(['error' => 'Book not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
