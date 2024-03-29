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
use App\Mail\CheckoutEmail;
use Illuminate\Support\Facades\Mail;

class BooksController extends Controller
{
    public function index()
    {
        return Inertia::render('Books/Index', [
            'books' => Book::query()
                ->orderBy('book_title')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('book_title', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(function ($book) {
                    $checkoutMarkers = $book->checkoutMarkers();
                    $distanceTravelled = round(BookCheckout::calculateHaversineDistance($checkoutMarkers), 0);

                    return [
                        'uuid' => $book->uuid,
                        'book_title' => $book->book_title,
                        'book_author' => $book->book_author,
                        'book_genre' => $book->book_genre,
                        'image' => $book->image,
                        'book_checkout' => $book->bookCheckout,
                        'distance_travelled' => $distanceTravelled,
                        'isOwner' => Auth::user() ? $book->user_id_owner == Auth::user()->id : null,
                        'userHasRead' => Auth::user() ? $book->bookCheckout->pluck('user_id')->contains(Auth::user()->id) : null,
                    ];
                }),
            'filters' => Request::only(['search']),
        ]);

    }

    public function bookshelf()
    {
        return Inertia::render('Books/Bookshelf', [
            'books' => Book::with('bookCheckout')
                ->orderBy('user_id_owner')
                ->orderBy('book_title')
                ->where(function ($query) {
                    $query->where('user_id_owner', Auth::id())
                        ->orWhereHas('bookCheckout', function ($subQuery) {
                            $subQuery->where('user_id', Auth::id());
                        });
                })
                ->get()
                ->transform(function ($book) {
                    $checkoutMarkers = $book->checkoutMarkers();
                    $distanceTravelled = round(BookCheckout::calculateHaversineDistance($checkoutMarkers), 0);
                    
                    return [
                        'uuid' => $book->uuid,
                        'book_title' => $book->book_title,
                        'book_genre' => $book->book_genre,
                        'book_author' => $book->book_author,
                        'isOwner' => Auth::user() ? $book->user_id_owner == Auth::user()->id : null,
                        'userHasRead' => $book->bookCheckout->pluck('user_id')->contains(Auth::user()->id),
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
            $genre = $item['volumeInfo']['categories'][0] ?? null;
            $publishedDate = $item['volumeInfo']['publishedDate'] ?? null;

            $filterItems[] = [
                'title' => $title,
                'description' => $description,
                'author' => $author,
                'thumbnail' => $thumbnail,
                'genre' => $genre,
                'published' => $publishedDate
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
            'description' => Request::get('description'),
            'book_genre' => Request::get('book_genre'),
            'published' => Request::get('published'),
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

        $firstBook = $book->bookCheckout->first();
        $mapCenter = [
            'lat' => floatval($firstBook ? $firstBook->lat : 0),
            'lng' => floatval($firstBook ? $firstBook?->lng : 0),
        ];

        return Inertia::render('Books/Show', [
            'book' => $book,
            'comments' => $comments,
            'isOwner' => Auth::user() ? $book->user_id_owner == Auth::user()->id : null,
            'userHasRead' => Auth::user() ? $book->bookCheckout->pluck('user_id')->contains(Auth::user()->id) : null,
            'isCheckedOut' => $book->isBookCheckedOut(),
            'isWithUser' => $book->isBookWithUser(),
            'markers' => $book->checkoutMarkers(),
            'mapCenter' => $mapCenter,
            'distanceTravelled' => round(BookCheckout::calculateHaversineDistance($book->checkoutMarkers()), 0),
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
        // or if user has already checked out the book, then they know the reference, so dont need to enter it ('na')
        // todo - ref alos not required if user scans qr code in the book
        if ($request->input('reference') == $book->reference || $request->input('reference') == 'na') {

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

            // send email to book owner when someone checks their book out
            if ( Auth::user()->id != $book->user_id_owner ) {
                Mail::to(Auth::user()->email)->send(new CheckoutEmail(Auth::user()->name, $book->book_title, $book->uuid));
            }

            return response()->json([
                'data' => 'Book checked out! Now enjoy reading and dont forget to check the book back in before passing to someone else!',
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

        return response()->json([
            'data' => 'book returned. now pass to a friend or charity shop for the next reader to enjoy!.',
            'success' => true,
        ]);

        // return Redirect::back()->with('success', '');

    }

    public function update(\Illuminate\Http\Request $request, Book $book)
    {
        // if uploading an image from Google Books API then generate unique id and store the image data in 'public/images' directory
        $googleBooksImage = $request->input('book_cover');
        $uploadedImage = $request->file('book_cover');

        // only update book_cover if a new image has been added.
        if ( $googleBooksImage && $googleBooksImage != $book->book_cover ) {

            try {
                $imageData = file_get_contents($googleBooksImage);
                $filename = uniqid() . '.jpg';
                Storage::disk('public')->put('images/' . $filename, $imageData);
                $image = 'images/' . $filename;
            } catch (\Exception $e) {
                logger($e->getMessage());
            }

        } else if ( $uploadedImage ) {
            $image = $request->file('book_cover')->store('images', 'public');
        }

        // dont update book_cover if no new image has been added.
        $book->update([
            // 'book_cover' => $image ?? null,
            'book_cover' => ($googleBooksImage == $book->book_cover) ? $book->book_cover : ($image ?? null),
            'book_title' => Request::get('book_title'),
            'book_author' => Request::get('book_author'),
            'book_genre' => Request::get('book_genre'),
            'description' => Request::get('description'),
            'published' => Request::get('published'),
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

}



