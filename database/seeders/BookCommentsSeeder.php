<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use App\Models\BookComments;
use Illuminate\Database\Seeder;

class BookCommentsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Populate some comments for a random selection of books
        $randomBookIds = Book::inRandomOrder()->limit(15)->pluck('id')->toArray();
        foreach ($randomBookIds as $bookId) {
            
            $book = Book::find($bookId);
            $randomNumberOfComments = rand(1, 5);

            // create a random number of comments for each book
            for ($i = 1; $i <= $randomNumberOfComments; $i++) {

                BookComments::factory()->create([
                    'book_id' => $book['id'],
                    'user_id' => User::inRandomOrder()->first()->id,
                ]);            
            }
        }
    }
}
