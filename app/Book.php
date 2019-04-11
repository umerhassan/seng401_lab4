<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
      'ISBN', 'name', 'author', 'year', 'publisher', 'image',
    ];


    public function subscribers(){
      return $this->hasMany('App\Subscription');
    }

    public function comments(){
      return $this->hasMany('App\Comment');
    }


}
