<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\BookCommentsController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
// use Illuminate\Support\Facades\Auth;

// Auth
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
Route::post('logout', [LoginController::class, 'destroy'])->middleware('auth');

// Dashboard
Route::get('/', function () {
    return Inertia::render('Home');
});

// Users
Route::middleware('auth')->group(function () {

    Route::get('/users', function () {
        return Inertia::render('Users/Index', [
            'users' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    // 'can' => [
                    //     'edit' => Auth::user()->can('edit', $user)
                    // ]
                ]),

            'filters' => Request::only(['search']),
            // 'can' => [
            //     'createUser' => Auth::user()->can('create', User::class)
            // ]
        ]);
    });

    // Route::get('/users/create', function () {
    //     return Inertia::render('Users/Create');
    // })->can('create', 'App\Models\User');

    // Route::post('/users', function () {
    //     $attributes = Request::validate([
    //         'name' => 'required',
    //         'email' => ['required', 'email'],
    //         'password' => 'required',
    //     ]);
    //     User::create($attributes);
    //     return redirect('/users');
    // });

});

Route::get('/users/create', function () {
    return Inertia::render('Users/Create');
})->name('register');

Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/profile/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('auth');
Route::post('/users/profile/update/{user}', [UserController::class, 'update'])->name('user.update')->middleware('auth');
Route::get('logout', [UserController::class, 'logout'])->name('logout');



// Books
Route::post('books/{book}/checkout', [BooksController::class, 'checkout'])->name('book.checkout')->middleware('auth');
Route::post('books/{book}/return', [BooksController::class, 'returnbook'])->name('book.return')->middleware('auth');
Route::get('books', [BooksController::class, 'index'])->name('books');
Route::get('books/create', [BooksController::class, 'create'])->name('book.create')->middleware('auth');
Route::post('books/store', [BooksController::class, 'store'])->name('book.store')->middleware('auth');
Route::post('books/store/search', [BooksController::class, 'storeSearch'])->name('book.store.search')->middleware('auth');


Route::get('books/{book}/edit', [BooksController::class, 'edit'])->name('book.edit')->middleware('auth');

// Route::post('books/{id}', [BooksController::class, 'update'])->name('book.update')->middleware(['auth']);

Route::post('/books', [BooksController::class, 'search'])->name('search.reference');

Route::get('books/{book}', [BooksController::class, 'show'])->name('book.show');
Route::get('bookshelf', [BooksController::class, 'bookshelf'])->name('book.bookshelf')->middleware('auth');
Route::post('books/{book}', [BookCommentsController::class, 'create'])->name('book.comment')->middleware('auth');
Route::post('books/{book}/{bookComments}', [BookCommentsController::class, 'delete'])->name('book.comment.delete')->middleware('auth');
Route::put('books/{book}/{bookComments}', [BookCommentsController::class, 'update'])->name('book.comment.update')->middleware('auth');


Route::post('/submit-form/{book}', [BooksController::class, 'update'])->name('submit.form');

