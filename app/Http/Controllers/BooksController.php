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
                ->through(function ($book) {
                    $checkoutMarkers = Book::checkoutMarkers($book);
                    $distanceTravelled = round(BookCheckout::calculateHaversineDistance($checkoutMarkers), 0);

                    return [
                        'uuid' => $book->uuid,
                        'book_title' => $book->book_title,
                        'book_author' => $book->book_author,
                        'book_genre' => $book->book_genre,
                        'image' => $book->image,
                        'book_checkout' => $book->bookCheckout,
                        'distance_travelled' => $distanceTravelled,
                    ];
                }),
            'filters' => Request::only(['search']),
        ]);

    }

    public function bookshelf()
    {
        return Inertia::render('Books/Bookshelf', [
            'books' => Book::with('bookCheckout')
                ->orderBy('book_title')
                ->where(function ($query) {
                    $query->where('user_id_owner', Auth::id())
                        ->orWhereHas('bookCheckout', function ($subQuery) {
                            $subQuery->where('user_id', Auth::id());
                        });
                })
                ->get()
                ->transform(function ($book) {
                    $checkoutMarkers = Book::checkoutMarkers($book);
                    $distanceTravelled = round(BookCheckout::calculateHaversineDistance($checkoutMarkers), 0);
                    
                    return [
                        'uuid' => $book->uuid,
                        'book_title' => $book->book_title,
                        'book_author' => $book->book_author,
                        'isOwner' => $book->user_id_owner == Auth::user()->id,
                        'image' => $book->image,
                        'book_checkout' => $book->bookCheckout,
                        'distance_travelled' => $distanceTravelled,
                    ];
                }),
        ]);
    }

    public function create()
    {
        return Inertia::render('Books/Create');
    }


    // call Google Books API and return all books matching the entered book_title
    public function storeSearch(HttpRequest $request)
    {
        $baseURL = "https://www.googleapis.com/books/v1/volumes";
        $queryParams = [
            'q' => $request->input('book_title'),
            'startIndex' => $request->input('startIndex', 0),
            'maxResults' => $request->input('maxResults', 20),
        ];

        $url = $baseURL . '?' . http_build_query($queryParams);
        $data = file_get_contents($url);
        $response = json_decode($data, true);

        $items = $response['items'] ?? [];
        $filterItems = [];

        foreach ($items as $item) {
            $title = $item['volumeInfo']['title'] ?? null;
            $description = $item['volumeInfo']['description'] ?? null;
            $author = $item['volumeInfo']['authors'][0] ?? null;
            $thumbnail = $item['volumeInfo']['imageLinks']['thumbnail'] ?? null;

            $filterItems[] = [
                'title' => $title,
                'description' => $description,
                'author' => $author,
                'thumbnail' => $thumbnail
            ];
        }

        return response()->json(['data' => $filterItems]);
    }
    
    /**
     * @throws \Exception
     */
    public function store(HttpRequest $request)
    {
        Request::validate([
            'book_title' => ['required'],
            'book_author' => ['required'],
        ]);

        // if uploading an image from Google Books API then generate unique id and store the image data in 'public/images' directory
        $googleBooksImage = $request->input('book_cover');
        $uploadedImage = $request->file('book_cover');

        if ( $googleBooksImage ) {
            $imageData = file_get_contents($googleBooksImage);
            $filename = uniqid() . '.jpg';
            Storage::disk('public')->put('images/' . $filename, $imageData);
            $image = 'images/' . $filename;
        } else if ( $uploadedImage ) {
            $image = $request->file('book_cover')->store('images', 'public');
        }
        
        $book = Book::create([
            'reference' => Book::generateUniqueReference(),
            'book_title' => Request::get('book_title'),
            'book_author' => Request::get('book_author'),
            'user_id_owner' => Auth::id(),
            'book_cover' => $image ?? null,
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

        return Redirect::route('book.show', ['book' => $book->uuid])->with('success', 'New book created.');

    }

    /**
     * Show individual book.
     *
     */
    public function show(Book $book)
    {
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
        $checkoutMarkers = Book::checkoutMarkers($book);
        $distanceTravelled = round(BookCheckout::calculateHaversineDistance($checkoutMarkers), 0);

        $firstBook = $book->bookCheckout->first();
        $mapCenter = [
            'lat' => floatval($firstBook ? $firstBook->lat : 0),
            'lng' => floatval($firstBook ? $firstBook?->lng : 0),
        ];

        return Inertia::render('Books/Show', [
            'book' => $book,
            'comments' => $comments,
            'isOwner' => Auth::user() && $book->user_id_owner == Auth::user()->id,
            'isCheckedOut' => $isCheckedOut,
            'isWithUser' => $isWithUser,
            'lastWithUser' => $lastWithUser,
            'markers' => $checkoutMarkers,
            'mapCenter' => $mapCenter,
            'distanceTravelled' => $distanceTravelled,
            'loggedIn' => auth()->check(),
        ]);
    }
    

    public function edit(Book $book)
    {
        return Inertia::render('Books/Edit', compact('book'));
    }

    public function checkout(Book $book, HttpRequest $request)
    {
        // user must enter the correct reference number to checkout book.
        if ($request->input('reference') == $book->reference) {

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

        return response()->json([
            'data' => 'Book checked out',
            'success' => true,
        ]);

        } else {

            return response()->json([
                'data' => 'Reference doesnt match.',
                'success' => false,
            ]);
        }
    }

    public function returnbook(Book $book)
    {
        //maybe check current user is the last person to check out the book? also add confirmation modal to page
        $book->bookCheckout()->update([
            'checkin_date' => Carbon::now(),
        ]);

        return Redirect::back()->with('success', 'book returned. now pass to a friend or charity shop for the next reader to enjoy!');

    }

    public function update(\Illuminate\Http\Request $request, Book $book)
    {
        $path = null;
        if ($request->hasFile('book_cover')) {
            // Delete the existing file if it exists
            if ($book->book_cover) {
                Storage::disk('public')->delete($book->book_cover);
            }

            // Store the file and get the path
            $path = $request->file('book_cover')->store('images', 'public');
        }

        $image = $path;
        if ( !$path && $book->book_cover ) {
            $image = $book->book_cover;
        }

        $book->update([
            'book_cover' => $image,
            'book_title' => Request::get('book_title'),
            'book_author' => Request::get('book_author'),
            'book_genre' => Request::get('book_genre'),
            'description' => Request::get('description'),
        ]);

        return Redirect::back()->with('success', 'book updated.');
    }

    /**
     * Redirect to book if search term matches a book reference.
     *
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function search(HttpRequest $request)
    {
        $query = $request->input('query');

        $book = Book::where('reference', $query)->get();

        if ($book->isEmpty()) {
            return Redirect::back()->with('failure', 'Book not found.');
        } else {
            return redirect()->route('book.show', ['book' => $book->first()]);
        }
    }

    // public function update(Book $id, HttpRequest $request)
    // {
    //     dd('where is this being called');
    //     // $image = $request->file('image');
    //     // $imageName = time().'.'.$image->getClientOriginalExtension();

        
    //     try {
    //         // Log request data before dd
    //         logger($request->all());

    //         // Dump and die to inspect the data
    //         dd($request->all());

    //         // Rest of your update logic...
    //     } catch (\Exception $e) {
    //         // Log the exception
    //         logger($e->getMessage());

    //         // Return a response indicating failure
    //         return response()->json(['error' => 'Internal Server Error'], 500);
    //     }

    //     return Redirect::back()->with('success', 'book updated.');
    // }

}



