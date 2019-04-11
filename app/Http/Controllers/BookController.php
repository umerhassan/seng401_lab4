<?php

namespace App\Http\Controllers;

use App\User;
use App\Book;
use App\Subscription;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class BookController extends Controller
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
    public function index()
    {
      $booksInfo = Book::leftJoin(
        'subscriptions', 'books.id', '=', 'subscriptions.book_ID'
      )->select(
        'books.id as bookID',
        'books.ISBN as ISBN',
        'books.name as name',
        'books.author as author',
        'books.year as year',
        'books.publisher as publisher',
        'books.image as image',
        'books.status as status',
        'subscriptions.id as subID',
        'subscriptions.book_ID as book_ID',
        'subscriptions.user_ID as user_ID',
        'subscriptions.subscribing as subscribing',
      )->orderBy('bookID', 'asc')->get();

      return view('books', ['booksInfo' => $booksInfo]);
    }

    /**
     * Subscribe/Unsubscribe the books
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        // Check if this user ID corresponds to an exsting user and book ID corresponds to an existing book
        if (User::where('id', '=', $request['user_ID'])->exists() &&
            Book::where('id', '=', $request['book_ID'])->exists()) {
            // If it is a subscribe request
            if ($request['action'] === "subscribe") {
              // Check if this user has yet susbcribed to this book in the past
              if (Subscription::where('book_ID', '=', $request['book_ID'])->where('user_ID', '=', $request['user_ID'])->exists()) {
                // Update the existing record in the "subscriptions" table
                Subscription::where([
                  'book_ID' => $request['book_ID'],
                  'user_ID' => $request['user_ID'],
                ])->update([
                  'subscribing' => 'yes',
                ]);
              }
              else {
                // Insert a new record into the "subscriptions" table
                Subscription::create([
                  'book_ID' => $request['book_ID'],
                  'user_ID' => $request['user_ID'],
                  'subscribing' => 'yes',
                ]);
              }

              // Change book's status
              Book::where('id', '=', $request['book_ID'])->update([
                'status' => 'unavailable',
              ]);
            }
            // If it is an unsubscribe request
            else if ($request['action'] === "unsubscribe") {
              // Delete the record from the "subscriptions" table
              Subscription::where([
                ['book_ID', '=', $request['book_ID']],
                ['user_ID', '=', $request['user_ID']],
              ])->update([
                'subscribing' => 'no',
              ]);
              // Change book's status
              Book::where('id', '=', $request['book_ID'])->update([
                'status' => 'available',
              ]);
            }
        }

        return redirect(BookController::$redirectTo);
    }

    /**
     * Change the book
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changeBook(Request $request)
    {
      // Check if this user is an admin
      if (User::where('id', '=', \Auth::user()->id)->first()->role === "admin") {
        // Check if this author is existed
        if (Book::where('id', '=', $request['book_ID'])->exists()) {
          Book::where('id', '=', $request['book_ID'])->update([
            'ISBN' => $request['book_ISBN'],
            'name' => $request['book_name'],
            'author' => $request['book_author'],
            'year' => $request['book_year'],
            'publisher' => $request['book_publisher'],
            'image' => $request['book_image'],
            'status' => 'available',
          ]);
        }
        else {
          Book::create([
            'ISBN' => $request['book_ISBN'],
            'name' => $request['book_name'],
            'author' => $request['book_author'],
            'year' => $request['book_year'],
            'publisher' => $request['book_publisher'],
            'image' => $request['book_image'],
            'status' => 'available',
          ]);
        }
      }
      else {
        return redirect('logout');
      }

      return redirect("/home");
    }

    /**
     * Remove the book
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function removeBook(Request $request)
    {
      // Check if this user is an admin
      if (User::where('id', '=', \Auth::user()->id)->first()->role === "admin") {
        // Check if this author is existed
        if (Book::where('id', '=', $request['book_ID'])->exists()) {
          Book::where('id', '=', $request['book_ID'])->delete();

          // Unsubscribe the user who is currently subscribing this book
          if (Subscription::where('book_ID', '=', $request['book_ID'])->where('subscribing', '=', 'yes')->exists()) {
            Subscription::where('book_ID', '=', $request['book_ID'])
              ->update(['subscribing' => 'no']);
          }
        }
      }
      else {
        return redirect('logout');
      }

      return redirect("/home");
    }
}
