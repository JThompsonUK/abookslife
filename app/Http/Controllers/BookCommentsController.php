<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Book;
use App\Models\BookComments;
use App\Models\UsersBooks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class BookCommentsController extends Controller
{

    public function create(Book $book, BookComments $bookComments)
    {
        $bookComments->create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'comment' => Request::get('comment'),
        ]);

        return Redirect::back()->with('success', 'new comment added.');
    }

    public function update(Book $book, BookComments $bookComments)
    {
        $bookComments->update([
            'comment' => Request::get('editedComment'),
        ]);

        return Redirect::back()->with('success', 'comment updated.');
    }

    public function delete(Book $book, BookComments $bookComments)
    {
        $bookComments->delete();

        return Redirect::back()->with('success', 'comment deleted.');
    }

}



