<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCheckout;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BookCheckoutSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $cities = include(database_path('seeders/ListOfCities.php'));
        $books = Book::get();

        // for each book, create a checkout record for the book owner
        foreach ($books as $book) {
            
            $startDate = Carbon::createFromFormat('Y-m-d', '2020-01-01');
            $endDate = Carbon::createFromFormat('Y-m-d', '2022-12-31');
            $randomDate = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));
            $checkinDate = rand(1, 10) == 0 ? null : Carbon::parse($randomDate)->addWeeks(2);
            $randomCity = $cities[array_rand($cities)];

            BookCheckout::create([
                'user_id' => $book->user_id_owner,
                'book_id' => $book->id,
                'checkout_date' => $randomDate,
                'checkin_date' => $checkinDate ? $checkinDate->toDateString() : null,
                'city' => $randomCity['name'],
                'lat' => $randomCity['lat'],
                'lng' => $randomCity['lng'],
            ]);
        }

        // loop checkouts. eg one book could potentially have 10 checkouts
        for ($i = 0; $i < 10; $i++) {

            $randomBookIds = Book::inRandomOrder()->limit(10)->pluck('id')->toArray();
            foreach ($randomBookIds as $bookId) {
                $book = Book::find($bookId);
                
                // set new checkout date to be 6 weeks after original checkout
                $checkoutDate = $book->bookCheckout->last()->checkout_date;
                $checkoutDateFormatted = Carbon::parse($checkoutDate)->addWeeks(6);

                // make checkin date 2 weeks after new checkout date
                $checkinDate = rand(1, 10) == 0 ? null : Carbon::parse($checkoutDateFormatted)->addWeeks(2);

                $randomCity = $cities[array_rand($cities)];

                // Get a random user who doesn't exist in the bookCheckout table
                $existingUserIds = $book->bookCheckout->pluck('user_id')->toArray();
                $randomUser = User::whereNotIn('id', $existingUserIds)->inRandomOrder()->first();

                BookCheckout::create([
                    'user_id' => $randomUser->id,
                    'book_id' => $book->id,
                    'checkout_date' => $checkoutDateFormatted,
                    'checkin_date' => $checkinDate ? $checkinDate : null,
                    'city' => $randomCity['name'],
                    'lat' => $randomCity['lat'],
                    'lng' => $randomCity['lng'],
                ]);
            }
        }
    }
}
