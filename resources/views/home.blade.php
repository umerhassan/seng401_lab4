@extends('layouts.app')

@section('css')
<style>
html, body {
  background-color: black;
  font-size: 20px;
  font-style: strong;

}

.home-section {
  border-bottom: 1px solid grey;

}

#role {
  text-align: right;
  color: red;
}
table {
  width: 100%;
}
.list th{
  background-color: black;
  color: white;
  text-align: center;
}
.list {
  margin: 1em 0;
  border: 3px solid red;
  border-radius: 1px;
  padding: 3em 1em;
  overflow: auto;
}

.list td{
  max-width: 200px;

}
tr:nth-child(even) {background-color: #CCC;}

form {
  margin: 1em 0;
}

.input-box {
  margin: 0.25em 0;
  border: 1px solid red;
  border-radius: 6px;
}

form > .button{
  margin: 0.25em 0 1em 0;
  background-color: red;
  color: white;
}


</style>
@endsection

@section('content')
<div class="container">
    <div class="justify-content-center">
      <div class="card">
          <div class="card-header">Dashboard</div>

          <div class="card-body">
              @if (session('status'))
                  <div class="alert alert-success" role="alert">
                      {{ session('status') }}
                  </div>
              @endif

              <div id="role">
               [<strong>{{$role}}</strong>]
              </div>
              <br>

              <div class="home-section" id="borrow-list">
                <h4>Your Books</h4>
                @if(count($borrows)>0)
                <table border=1 class="list">
                  <tr>
                    <th>Book ID</th>
                    <th>Book Name</th>
                    <th>Currently Subscribing</th>
                  </tr>
                  @foreach ($borrows as $key => $data)
                    <tr>
                      <td>{{$data->book_ID}}</td>
                      <td>{{$data->bookName}}</td>
                      <td>{{$data->subscribing}}</td>
                    </tr>
                  @endforeach
                </table>
                @else
                  <p> You haven't subscribed to any books. </p>
                @endif
                <!-- <table border=1 class="list">
                  @foreach ($borrows as $key => $data)
                  <p>Book ID : {{$data->book_ID}}</p>
                  <p>Book Name : {{$data->bookName}}</p>
                  <p>Status: {{$data->subscribing}} </p>
                  <hr color="black">
                    @endforeach -->
              </div>

              <br>

              @if ($role === 'admin')

              <div class="home-section" id="books-list">
                <h4>Books Table</h4>
                <table border=1 class="list">
                  <tr>
                    <th>ID</th>
                    <th>ISBN</th>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Published Year</th>
                    <th>Publisher</th>
                    <th>Image</th>
                    <th>Status</th>
                  </tr>
                  @foreach ($books as $key => $data)
                    <tr>
                      <td>{{$data->id}}</td>
                      <td>{{$data->ISBN}}</td>
                      <td>{{$data->name}}</td>
                      <td>{{$data->author}}</td>
                      <td>{{$data->year}}</td>
                      <td>{{$data->publisher}}</td>
                      <td>{{$data->image}}</td>
                      <td>{{$data->status}}</td>
                    </tr>
                  @endforeach
                </table>
                <form method="POST" action="{{url('books/changeBook')}}">
                  @csrf
                  <h3>Update Book </h3>
                  <label>Book ID</label>
                  <input class="input-box" type="text" name="book_ID" value="">

                  <label>ISBN</label>
                  <input class="input-box" type="text" name="book_ISBN" value="">

                  <label>Book Name</label>
                  <input class="input-box" type="text" name="book_name" value="">

                  <label>Book Author</label>
                  <input class="input-box" type="text" name="book_author" value="">
                  <br>
                  <label>Published Year</label>
                  <input class="input-box" type="text" name="book_year" value="">

                  <label>Publisher</label>
                  <input class="input-box" type="text" name="book_publisher" value="">

                  <label>Image</label>
                  <input class="input-box" type="text" name="book_image" value="">
                  <input class="btn button" type="submit" value="Update">
                </form>
                <form method="POST" action="{{url('books/removeBook')}}">
                  @csrf
                  <h3>Remove Book</h3>
                  <label>Book ID</label>
                  <input class="input-box" type="text" name="book_ID" value="">
                  <input class="btn button" type="submit" value="Remove">
                </form>
              </div>

              <br>

                <div class="home-section" id="user-list">
                  <h4>Users Table</h4>
                  <table border=1 class="list">
                    <tr>
                      <th>ID</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Birthday</th>
                      <th>Education</th>
                      <th>Created At</th>
                    </tr>
                    @foreach ($users as $key => $data)
                      <tr>
                        <td>{{$data->id}}</td>
                        <td>{{$data->email}}</td>
                        <td>{{$data->role}}</td>
                        <td>{{$data->birthday}}</td>
                        <td>{{$data->education}}</td>
                        <td>{{$data->created_at}}</td>
                      </tr>
                    @endforeach
                  </table>
                  <form method="POST" action="{{url('users/changeRole')}}">
                    @csrf
                      <h3>Change Role </h3>
                    <label>User ID</label>
                    <input class="input-box" type="text" name="user_ID" value="">
                    <br>
                    <label>User Role</label>
                    <select class="input-box" name="user_role">
                      <option value="subscriber">Subscriber</option>
                      <option value="admin">Admin</option>
                    </select>
                    <input class="btn button" type="submit" value="Save">
                  </form>
                </div>

                <br>


                <div class="home-section" id="authors-list">
                  <h4>Authors Table</h4>
                  <table border=1 class="list">
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                    </tr>
                    @foreach ($authors as $key => $data)
                      <tr>
                        <td>{{$data->id}}</td>
                        <td>{{$data->name}}</td>
                      </tr>
                    @endforeach
                  </table>
                  <form method="POST" action="{{url('authors/changeAuthor')}}">
                    @csrf
                      <h3>Update Author </h3>
                    <label>Author ID</label>
                    <input class="input-box" type="text" name="author_ID" value="">
                    <label>Author Name</label>
                    <input class="input-box" type="text" name="author_name" value="">
                    <input class="btn button" type="submit" value="Update">
                  </form>
                  <form method="POST" action="{{url('authors/removeAuthor')}}">
                    @csrf
                      <h3>Remove Author </h3>
                    <label>Author ID</label>
                    <input class="input-box" type="text" name="author_ID" value="">
                    <input class="btn button" type="submit" value="Remove">
                  </form>
                </div>

                <br>

                <div class="home-section" id="subscriptions-list">
                  <h4>Subscriptions Table</h4>
                  <table border=1 class="list">
                    <tr>
                      <th>ID</th>
                      <th>Book ID</th>
                      <th>Book Name</th>
                      <th>User ID</th>
                      <th>Subscribing</th>
                    </tr>
                    @foreach ($subscriptions as $key => $data)
                      <tr>
                        <td>{{$data->id}}</td>
                        <td>{{$data->book_ID}}</td>
                        <td>{{$data->bookName}}</td>
                        <td>{{$data->user_ID}}</td>
                        <td>{{$data->subscribing}}</td>
                      </tr>
                    @endforeach
                  </table>
                  <form method="POST" action="{{url('subscriptions/changeSubscription')}}">
                    @csrf
                    <h3>Subscribe/Unsubscribe User to Book</h3>
                    <label>User ID</label>
                    <input class="input-box" type="text" name="user_ID" value="">
                    <label>Book ID</label>
                    <input class="input-box" type="text" name="book_ID" value="">

                    <label>Subscribing</label>
                    <select class="input-box" name="subscribing">
                      <option value="yes">Yes</option>
                      <option value="no">No</option>
                    </select>
                    <input class="btn button" type="submit" value="Update">
                  </form>
                </div>

                <br>

                <div class="home-section" id="comments-list">
                  <h4>Comments List</h4>
                  <table border=1 class="list">
                    <tr>
                      <th>ID</th>
                      <th>Book ID</th>
                      <th>Book Name</th>
                      <th>User ID</th>
                      <th>Comment</th>
                      <th>Updated At</th>
                    </tr>
                    @foreach ($comments as $key => $data)
                      <tr>
                        <td>{{$data->id}}</td>
                        <td>{{$data->book_ID}}</td>
                        <td>{{$data->bookName}}</td>
                        <td>{{$data->user_ID}}</td>
                        <td>{{$data->text}}</td>
                        <td>{{$data->updated_at}}</td>
                      </tr>
                    @endforeach
                  </table>
                </div>
              @endif

          </div>
      </div>
    </div>
</div>
@endsection
