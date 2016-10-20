@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h4>Accounts</h4>
        <ul class="list-group">
          @foreach($requests as $response)
            <a href="/{{$response->id}}/{{$response->advertiserName}}/objects"><li class="list-group-item">{{$response->advertiserName}}</li></a>
          @endforeach
        <ul>
    <div>
<div>
@stop
