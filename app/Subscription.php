<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
      'book_ID', 'user_ID', 'subscribing',
    ];
}
