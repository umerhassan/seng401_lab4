<?php

namespace App\Http\Controllers;

use App\User;
use App\Book;
use App\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
  /**
  * Where to redirect users after form action.
  *
  * @var string
  */
  protected static $redirectTo = '/home';

  /**
   * Change the subscription for a specific user
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function changeSubscription(Request $request)
  {
    // Check if this user is an admin
    if (User::where('id', '=', \Auth::user()->id)->first()->role === "admin") {
      // Check if inputs are valid
      if (User::where('id', '=', $request['user_ID'])->exists() &&
          Book::where('id', '=', $request['book_ID'])->exists() &&
          ($request['subscribing'] === "yes" || $request['subscribing'] === "no")) {

        // Check if there is already an user subscribing it
        if (Subscription::where('book_ID', '=', $request['book_ID'])->where('subscribing', '=', 'yes')->exists()) {
          // Unsubscribe for that user
          $unsubUserID = Subscription::where('book_ID', '=', $request['book_ID'])->where('subscribing', '=', 'yes')->first()->user_ID;
          Subscription::where([
            'book_ID' => $request['book_ID'],
            'user_ID' => $unsubUserID,
          ])->update([
            'subscribing' => 'no',
          ]);
        }

        // If there is already a subscription record for this user
        if (Subscription::where('book_ID', '=', $request['book_ID'])->where('user_ID', '=', $request['user_ID'])->exists()) {
          // Update the existing record in the "subscriptions" table
          Subscription::where([
            'book_ID' => $request['book_ID'],
            'user_ID' => $request['user_ID'],
          ])->update([
            'subscribing' => $request['subscribing'],
          ]);
        }
        else {
          // Insert a new record into the "subscriptions" table
          Subscription::create([
            'book_ID' => $request['book_ID'],
            'user_ID' => $request['user_ID'],
            'subscribing' => $request['subscribing'],
          ]);
        }

        // Change book's status
        if ($request['subscribing'] === "yes") {
          Book::where('id', '=', $request['book_ID'])->update([
            'status' => 'unavailable',
          ]);
        }
        else {
          Book::where('id', '=', $request['book_ID'])->update([
            'status' => 'available',
          ]);
        }
      }
    }
    else {
      return redirect('logout');
    }

    return redirect(SubscriptionController::$redirectTo);
  }
}
