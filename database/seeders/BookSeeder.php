<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        Book::firstOrCreate([
            'book_title' => "Harry Potter and the Philosopher's Stone",
            'book_author' => "J.K Rowling",
            'user_id_owner' => 1,
            'reference' => 'test1'
        ]);

        Book::firstOrCreate([
            'book_title' => "The Lord of the Rings",
            'book_author' => "J.R.R. Tolkien",
            'user_id_owner' => 2,
            'reference' => 'test2'
        ]);

        Book::firstOrCreate([
            'book_title' => "Jurassic Park",
            'book_author' => "Michael Crichton",
            'user_id_owner' => 3,
            'reference' => 'test3'
        ]);
    }
}
