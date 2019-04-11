@extends('layouts.app')

@section('title')
Books
@endsection

@section('css')
<style>
html, body {
    background-color: black;
    color: white;
    font-family: 'Nunito', sans-serif;
    font-weight: 200;
    height: 100vh;
    margin: 0;
}

.block-center {
    align-items: center;
    display: block;
    justify-content: center;
    margin: 0 5%;
}

.position-ref {
    position: relative;
}

.content {
    text-align: center;
}

.title {
    font-size: 84px;
    text-align: center;
}

.m-b-md {
    margin-bottom: 30px;
}

.book {
  display: inline-block;
  margin: 2em 0;
  border: 1px solid white;
  border-radius: 8px;
  width: 15%;
  min-height: 500px;
  vertical-align: top;

}

.book-cover {
  /* flex: 0 0 30%; */
  border-right: 1px solid white;
  padding: 0.5em;
}

.book-cover > img {
  width: 100px;
  height: 100px;
  align: center;

}

.book-info {
  /* flex: 1; */
  padding: 0.5em;
}



.book-info input{
  margin: 0.5em 0.5em 0 0;
  width: 150px;
  background-color: red;
  color: white;
}




</style>
@endsection

@section('content')
<div class="block-center position-ref">
  <div class="title m-b-md">
      BOOKS
  </div>
  <?php $set = array(); ?>
  <div class="book-list">
    @foreach ($booksInfo as $key => $data)
    <?php
    if (!array_key_exists($data->bookID, $set)) {
      $set[$data->bookID] = true; ?>
      <div class="book">
        <div class="book-cover">
          <img src="{{$data->image}}">
        </div>
        <div class="book-info">
          <p>Name: <span class="info-content">{{$data->name}}</span></p>
          <p>ISBN: <span class="info-content">{{$data->ISBN}}</span></p>
          <p>Author: <span class="info-content">{{$data->author}}</span></p>
          <p>Publication Year: <span class="info-content">{{$data->year}}</span></p>
          <p>Publisher: <span class="info-content">{{$data->publisher}}</span></p>

          @if (!Auth::guest())
            <form method="POST" action="{{url('books/subscribe')}}">
              @csrf
              <input type="hidden" name="book_ID" value="{{$data->bookID}}">
              <input type="hidden" name="user_ID" value="{{Auth::user()->id}}">
              @if ($data->status === "available")
                <input type="hidden" name="action" value="subscribe">
                <input class="btn" type="submit" value="Subscribe">
              @elseif ($data->status === "unavailable")
                @if ($data->user_ID === Auth::user()->id && $data->subscribing === "yes")
                  <input type="hidden" name="action" value="unsubscribe">
                  <input class="btn" type="submit" value="Unsubscribe">
                @elseif ($data->user_ID !== Auth::user()->id || ($data->user_ID === Auth::user()->id && $data->subscribing === "no"))
                  <input class="btn" type="submit" value="Subscribe" disabled>
                @endif
              @endif
            </form>
          @endif
          <form method="POST" action="{{url('books/comments')}}">
            @csrf
            <input type="hidden" name="book_ID" value="{{$data->bookID}}">
            <input class="btn" type="submit" value="View Comments">
          </form>
        </div>
      </div>
    <?php
    }
    ?>
    @endforeach
  </div>
</div>
@endsection
