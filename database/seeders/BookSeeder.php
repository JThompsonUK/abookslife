<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Populate books table with list of books
        $books = include(database_path('seeders/ListOfBooks.php'));
        foreach ($books as $book) {
            Book::factory()->create([
                'book_title' => $book['title'],
                'book_author' => $book['author'],
                'book_genre' => $book['genre'],
                'published' => is_numeric($book['year']) ? $book['year'] : null,
                'description' => $book['description'],
            ]);
        }
    }
}
