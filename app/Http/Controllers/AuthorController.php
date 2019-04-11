<?php

namespace App\Http\Controllers;

use App\User;
use App\Book;
use App\Author;
use App\Subscription;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
  /**
  * Where to redirect users after form action.
  *
  * @var string
  */
  protected static $redirectTo = '/home';

  /**
   * Change the author
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function changeAuthor(Request $request)
  {
    // Check if this user is an admin
    if (User::where('id', '=', \Auth::user()->id)->first()->role === "admin") {
      // Check if this author is existed
      if (Author::where('id', '=', $request['author_ID'])->exists()) {
        Author::where('id', '=', $request['author_ID'])->update(['name' => $request['author_name']]);
      }
      else {
        Author::create(['name' => $request['author_name']]);
      }
    }
    else {
      return redirect('logout');
    }

    return redirect("/home");
  }

  /**
   * Remove the author
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function removeAuthor(Request $request)
  {
    // Check if this user is an admin
    if (User::where('id', '=', \Auth::user()->id)->first()->role === "admin") {
      // Check if this author is existed
      if (Author::where('id', '=', $request['author_ID'])->exists()) {
        $author_name = Author::where('id', '=', $request['author_ID'])->first()->name;
        $author_name = trim($author_name) . "%";

        // Remove this author
        Author::where('id', '=', $request['author_ID'])->delete();

        // Remove the book whose author is this
        if (Book::where('author', 'like', $author_name)->exists()) {
          $book_ID = Book::where('author', 'like', $author_name)->select('id')->get();

          // Delete the book
          Book::where('author', 'like', $author_name)->delete();

          // Unsubscribe the user who is currently subscribing this book
          foreach ($book_ID as $key => $data) {
            if (Subscription::where('book_ID', '=', $data->id)->where('subscribing', '=', 'yes')->exists()) {
              Subscription::where('book_ID', '=', $data->id)
                ->update(['subscribing' => 'no']);
            }
          }
        }
      }
    }
    else {
      return redirect('logout');
    }

    return redirect("/home");
  }
}
