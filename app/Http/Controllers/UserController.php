<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class UserController extends Controller
{
  /**
   * Change the role of a specific user
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function changeRole(Request $request)
  {
    // Check if this user is an admin
    if (User::where('id', '=', \Auth::user()->id)->first()->role === "admin") {
      // Check if inputs are valid
      if (User::where('id', '=', $request['user_ID'])->exists() &&
          ($request['user_role'] === "subscriber" || $request['user_role'] === "admin")) {
        User::where('id', '=', $request['user_ID'])->update(['role' => $request['user_role']]);
      }
    }
    else {
      return redirect('logout');
    }

    return redirect('home');
  }
}
