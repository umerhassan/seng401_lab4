@extends('layouts.app')

@section('title')
Message
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
    margin: 0 20%;
}

.position-ref {
    position: relative;
}

.message {
  text-align: center;
  color: red;
  font-size: 32px;
}
</style>
@endsection

@section('content')
<div class="block-center position-ref">
  <p class="message">{{$message}}</p>
</div>
@endsection
