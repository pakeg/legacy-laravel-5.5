@extends('admin.main')

@section('content')
  <div class="container">
      <div class="row">
      	Админ: <h1>{{$user->name}}</h1>
      </div>
  </div>
@endsection