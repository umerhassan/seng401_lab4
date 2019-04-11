<?php

namespace App\Http\Controllers;

use App\User;
use App\Book;
use App\Author;
use App\Subscription;
use App\Comment;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $role = \Auth::user()->role;
        $borrows = Subscription::join('books', 'books.id', '=', 'subscriptions.book_ID')
          ->where('user_ID', '=', \Auth::user()->id)
          ->select(
            'subscriptions.*',
            'books.name as bookName',
          )->orderBy('book_ID', 'asc')->get();
        $users = User::orderBy('id', 'asc')->get();
        $books = Book::orderBy('id', 'asc')->get();
        $authors = Author::orderBy('id', 'asc')->get();
        $subscriptions = Subscription::join('books', 'books.id', '=', 'subscriptions.book_ID')
          ->select(
            'subscriptions.*',
            'books.name as bookName',
          )->orderBy('id', 'asc')->get();
        $comments = Comment::join('books', 'books.id', '=', 'comments.book_ID')
          ->select(
            'comments.*',
            'books.name as bookName',
          )->orderBy('id', 'asc')->get();

        return view('home', [
            'role' => $role,
            'borrows' => $borrows,
            'users' => $users,
            'books' => $books,
            'authors' => $authors,
            'subscriptions' => $subscriptions,
            'comments' => $comments,
          ]);
    }
}
