<?php

namespace App\Http\Controllers;

use App\User;
use App\Book;
use App\Subscription;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Input;

class CommentController extends Controller
{
    /**
    * Where to redirect users after form action.
    *
    * @var string
    */
    protected static $redirectTo = '/books';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($book_ID)
    {
        $bookName = Book::where('id', '=', $book_ID)->select('name')->first();
        $comments = Comment::where('book_ID', '=', $book_ID)->get();

        return view('comments', ['bookName' => $bookName,
          'bookID' => $book_ID,
          'comments' => $comments,
        ]);
    }

    /**
     * Handle the POST request and turn it into a GET request
     *
     * @return \Illuminate\Http\Response
     */
    public function processForm() {
        $id  = Input::get('book_ID');
        return redirect('books/comments/' . $id) ;
    }

    /**
     * Comment on a book
     *
     * @return \Illuminate\Http\Response
     */
    public function comment(Request $request) {
      // Check if this user ID corresponds to an exsting user and book ID corresponds to an existing book
      if (User::where('id', '=', $request['user_ID'])->exists() &&
          Book::where('id', '=', $request['book_ID'])->exists()) {

        // Check if this user has subscribed to this book
        if (Subscription::where('user_ID', '=', $request['user_ID'])->where('book_ID', '=', $request['book_ID'])->exists()) {
          // Check if this user has yet commented to this book in the past
          if (Comment::where('user_ID', '=', $request['user_ID'])->where('book_ID', '=', $request['book_ID'])->exists()) {
            // Update the existing record in the "comments" table
            Comment::where([
              'book_ID' => $request['book_ID'],
              'user_ID' => $request['user_ID'],
            ])->update([
              'text' => $request['book_comment'],
            ]);
          }
          else {
            // Insert a new record into the "comments" table
            Comment::create([
              'book_ID' => $request['book_ID'],
              'user_ID' => $request['user_ID'],
              'text' => $request['book_comment'],
            ]);
          }
        }
        else {
          $message = urlencode("You have not previously subscribed to this book");
          return redirect('message/' . $message);
        }
      }

      return redirect('books/comments/' . $request['book_ID']);
    }
}
