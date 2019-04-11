@extends('layouts.app')

@section('title')
Comments
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
    width: 100%;
    font-size: 30px;
}
.btn {
  background-color: red !important;
  color: white;
  font-size: 15px;
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

.title {
    font-size: 30px;
    text-align: center;
    color: white;
}

.m-b-md {
    margin-bottom: 30px;
}



.comment-area textarea {
  display: block;
  margin: 1em 0;
  width: 100%;
  height: 40px;
}

.comment-area input{
  margin: 0.5em 0.5em 0.5em 0;
  width: 100px;
  background-color: #ff9900;
}


iframe {
  margin-top: 20px;width:99%; height:980px;}

.comment-list {

  padding: 2em 1em;
}

.comment {
  border-bottom: 1px solid black;
  margin: 2em 0;
}
</style>
@endsection

@section('content')
<div class="block-center position-ref">
  <div class="title m-b-md">
    Name: {{$bookName->name}}
    <iframe src="https://www.youtube.com/embed/{{$publisher->publisher}}">
    </iframe>

  </div>
  <div class="comment-list">
    @foreach ($comments as $key => $data)
    <h3> Comments </h3>
      <div class="comment">
        {{$data->user_ID}} <span> | </span>
        {{$data->text}}
      </div>
    @endforeach
    @if (!Auth::guest())
    <h3> Post Comment </h3>
      <div class="comment-area">
        <form method="POST" action="{{url('comment')}}">
          @csrf
          <input type="hidden" name="book_ID" value="{{$bookID}}">


          <input type="hidden" name="user_ID" value="{{Auth::user()->id}}">
          <textarea name="book_comment"></textarea>
          <input class="btn" type="submit" value="Comment">
        </form>
      </div>
    @endif

  </div>
</div>
@endsection
