<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Requests\CommentRequest;
use App\Models\Book;
use App\Models\BookCheckout;
use App\Models\UsersBooks;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Stevebauman\Location\Facades\Location;
use Illuminate\Http\Request as HttpRequest;

class BooksController extends Controller
{
    public function index()
    {
        return Inertia::render('Books/Index', [
            'books' => Book::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('book_title', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(fn($book) => [
                    'id' => $book->id,
                    'book_title' => $book->book_title,
                    'book_author' => $book->book_author,
                    'book_genre' => $book->book_genre,
//                    'can' => [
//                        'edit' => Auth::user()->can('edit', $user)
//                    ]
                ]),

            'filters' => Request::only(['search']),
//            'can' => [
//                'createUser' => Auth::user()->can('create', User::class)
//            ]
        ]);

//        return Inertia::render('Books/Index', [
////            'filters' => Request::all('search', 'trashed'),
//            'books' => Book::orderBy('book_title')
//                ->get()
////                ->transform(fn ($books) => [
////                    'id' => $books->id,
////                    'book_title' => $books->book_title,
////                    'book_author' => $books->book_author,
////                ]),
//        ]);
    }

    public function bookshelf()
    {
        return Inertia::render('Books/Bookshelf', [
//            'filters' => Request::all('search', 'trashed'),
            'books' => Book::orderBy('book_title')
                ->where('user_id_owner', Auth::id())
                ->get()
                ->transform(fn ($books) => [
                    'id' => $books->id,
                    'book_title' => $books->book_title,
                    'book_author' => $books->book_author,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Books/Create');
    }

    /**
     * @throws \Exception
     */
    public function store()
    {

        Request::validate([
            'book_title' => ['required'],
            'book_author' => ['required'],
        ]);

        $book = Book::create([
            'reference' => Book::generateUniqueReference(),
            'code' => random_int(0001, 9999),
            'book_title' => Request::get('book_title'),
            'book_author' => Request::get('book_author'),
            'user_id_owner' => Auth::id(),
        ]);

        // Check if the app is running on localhost
        if (app()->isLocal()) {
            $ip = '86.176.180.25';
        } else {
            $ip = request()->ip();
        }

        // Get location information based on IP
        $location = Location::get($ip);

        BookCheckout::create([
            'user_id' => Auth::user()->id,
            'book_id' => $book->id,
            'checkout_date' => Carbon::now(),
            'lat' => is_null($location) ? null : $location->latitude,
            'lng' => is_null($location) ? null : $location->longitude,
            'city' => is_null($location) ? null : $location->cityName,
        ]);

        return Redirect::route('books')->with('success', 'Book created.');
    }


    public function show(Book $book)
    {
        $book->load('bookCheckout');
//        $book->load('bookComments');

        $comments = $book->bookComments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'user' => $comment->user->name,
                'isByActiveUser' => $comment->isCommentByActiveUser(),
                'created' => $comment->created_at->format('Y-m-d H:i:s'),
                'updated' => $comment->updated_at,
            ];
        })->sortByDesc('created')->values();

        $isCheckedOut = Book::isBookCheckedOut($book);
        $isWithUser = Book::isBookWithUser($book);
        $lastWithUser = Book::wasBookLastWithUser($book);


//        $markers = [
//            ['position' => ['lat' => 37.7749, 'lng' => -122.4194], 'title' => 'Marker 1'],
//            // Add more markers as needed
//        ];


        $markers = $book->bookCheckout->map(function ($book) {
            return [
                'lat' => floatval($book->lat),
                'lng' => floatval($book->lng),
            ];
        })->toArray();

        $firstBook = $book->bookCheckout->first();
        if ($firstBook) {
            $mapCenter = [
                'lat' => floatval($firstBook->lat),
                'lng' => floatval($firstBook->lng),
            ];
        } else {
            $mapCenter = [
                'lat' => 0,
                'lng' => 0,
            ];
        }

//        $sqlDistance = get_offers_near(40.7128, 34.0522, -74.0060, -118.2437);




        $distanceTravelled = round(BookCheckout::calculateHaversineDistance($markers), 0);


//        return Inertia::render('Books/Show', compact('book', 'isCheckedOut', 'isWithUser', '', '', '', ''));

        return Inertia::render('Books/Show', [
            'book' => $book,
            'comments' => $comments,
            'isOwner' => Auth::user() && $book->user_id_owner == Auth::user()->id,
            'isCheckedOut' => $isCheckedOut,
            'isWithUser' => $isWithUser,
            'lastWithUser' => $lastWithUser,
            'markers' => $markers,
            'mapCenter' => $mapCenter,
            'distanceTravelled' => $distanceTravelled,
        ]);

//        return Inertia::render('Books/Edit', $books);
//        return Inertia::render('Books/Edit', [
//            'books' => [
//                'id' => $books->id,
//                'book_title' => $books->book_title,
//                'book_author' => $books->book_author,
//                'user_id_owner' => $books->user_id_owner,
//                'user_id' => Auth::id(),
//                'checkouts' => $books->usersbooks,
//            ],
//            // info($books->book_title),
//        ]);
    }








    public function edit(Book $book)
    {
        return Inertia::render('Books/Edit', compact('book'));
//        return Inertia::render('Books/Edit', $books);
//        return Inertia::render('Books/Edit', [
//            'books' => [
//                'id' => $books->id,
//                'book_title' => $books->book_title,
//                'book_author' => $books->book_author,
//                'user_id_owner' => $books->user_id_owner,
//                'user_id' => Auth::id(),
//                'checkouts' => $books->usersbooks,
//            ],
//            // info($books->book_title),
//        ]);
    }


    public function update(Book $id, HttpRequest $request)
    {


        dd($request->all());
        try {
            // Log request data before dd
            logger($request->all());

            // Dump and die to inspect the data
            dd($request->all());

            // Rest of your update logic...
        } catch (\Exception $e) {
            // Log the exception
            logger($e->getMessage());

            // Return a response indicating failure
            return response()->json(['error' => 'Internal Server Error'], 500);
        }


//        dd('testsssss');
//dd($request->all());
//        Request::validate([
//            'book_title' => ['required'],
//            'book_author' => ['required'],
//            'image' => 'image|max:1024'
//        ]);
//dd($request->hasFile('book_cover'));
//        $path = null;
//        if ($request->hasFile('book_cover')) {
//            // Delete the existing file if it exists
//            if ($book->book_cover) {
//                Storage::disk('public')->delete($book->book_cover);
//            }
//            // Store the file and get the path
//            $path = $request->file('book_cover')->store('book_covers', 'public');
//        }
//
//        $book->update([
////            'book_cover' => $path,
////            'book_title' => Request::get('book_title'),
////            'book_author' => Request::get('book_author'),
////            'book_genre' => Request::get('book_genre'),
////            'description' => Request::get('description'),
//        ]);
//
        return Redirect::back()->with('success', 'book updated.');
    }

    public function checkout(Book $book)
    {
        // Check if the app is running on localhost
        if (app()->isLocal()) {
            $ip = '86.176.180.25';
        } else {
            $ip = request()->ip();
        }

        // Get location information based on IP
        $location = Location::get($ip);

        BookCheckout::create([
            'user_id' => Auth::user()->id,
            'book_id' => $book->id,
            'checkout_date' => Carbon::now(),
            'lat' => is_null($location) ? null : $location->lat,
            'lng' => is_null($location) ? null : $location->lon,
            'city' => is_null($location) ? null : $location->city,
        ]);

        return Redirect::back()->with('success', 'book checked out.');
    }



    public function submitForm(\Illuminate\Http\Request $request, Book $book)
    {
//        dd($request->hasFile('book_cover'));
        $path = null;
        if ($request->hasFile('book_cover')) {
            // Delete the existing file if it exists
            if ($book->book_cover) {
                Storage::disk('public')->delete($book->book_cover);
            }
            // Store the file and get the path
//            $path = $request->file('book_cover')->store('book_covers', 'public');
//            $path = Storage::put('covers', $request->file('book_cover'));
            $path = $request->file('book_cover')->store('images', 'public');

//            $path = Storage::disk('local')->put('book_covers', $request->file('book_cover'));


        }

        $book->update([
            'book_cover' => $path,
            'book_title' => Request::get('book_title'),
            'book_author' => Request::get('book_author'),
            'book_genre' => Request::get('book_genre'),
            'description' => Request::get('description'),
        ]);

        return Redirect::back()->with('success', 'book updated.');
    }

}



