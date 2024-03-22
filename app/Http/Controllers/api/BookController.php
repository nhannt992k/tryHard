<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Image;
use Exception;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Book::query()
                ->join('images', 'images.book_id', 'books.id')
                ->select('books.id', 'books.price', 'books.quantity', 'images.image')
                ->where('images.is_default', 1)->get();
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
        
            $book = Book::create($request->all());
            return response()->json(['success'=> 'Create book successfully'],Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['error'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        try {
            $data = Book::query()
                ->join('authors', 'authors.id', 'books.author_id')
                ->join('publishers', 'publishers.id', 'books.publisher_id')
                ->select('books.id', 'books.name', 'books.quantity', 'books.price', 'authors.name', 'publishers.name')
                ->where('books.id', $book->id)
                ->first();

            if (empty($data)) {
            }
            $images = Image::where('book_id', $data->id)->pluck('image')->toArray();
            $data = $data->toArray();
            $data['images'] = $images;
            return response()->json($data);
          
        } catch (Exception $e) {
            return response()->json(['message' => 'Book not found'],Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookController $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
