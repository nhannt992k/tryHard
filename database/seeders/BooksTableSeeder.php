<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Book;
use App\Models\Category;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Create 5 authors
          for ($i = 1; $i <= 5; $i++) {
            $author = new Author();
            $author->name = 'Author ' . $i;
            $author->save();
        }

        // Create 3 publishers
        for ($i = 1; $i <= 3; $i++) {
            $publisher = new Publisher();
            $publisher->name = 'Publisher ' . $i;
            $publisher->save();
        }

        //Crate 5 categories
        for ($i = 1; $i <= 5; $i++) {
            $category = new Category();
            $category->name = 'Category ' . $i;
            $category->save();
        }
        // Create 20 books
        for ($i = 1; $i <= 20; $i++) {
            $book = new Book();
            $book->name = 'Book ' . $i;
            $book->author_id = rand(1, 5);
            $book->publisher_id = rand(1, 3);
            $book->category_id = rand(1, 5);
            $book->quantity = rand(50, 100);
            $book->price = rand(100000, 900000);
            $book->save();

            // Create 3 images for each book
            for ($j = 1; $j <= 3; $j++) {
                $image = new Image();
                $image->book_id = $book->id;
                $image->image = 'Image ' . $j;
                $image->is_default = ($j == 1) ? true : false;
                $image->save();

              
            }
        }
    }
}
