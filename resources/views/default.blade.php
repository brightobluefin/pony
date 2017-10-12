@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h4>Accounts</h4>
        @if(\app()->environment() != 'production')
            <a href="/account-new" class="btn btn-primary" >Create New Account</a>
        @endif
        <ul class="list-group">
          @foreach($requests as $response)
           <li class="list-group-item">
               <a href="/{{$response->id}}/{{$response->advertiserName}}/objects">{{$response->advertiserName}}</a>
               @if(\app()->environment() != 'production')
                   <a href="/delete-account/{{$response->id}}" onclick="return confirm('Are you sure you want to Delete?')"  class="btn btn-default pull-right">Delete </a>
               @endif
           </li>
          @endforeach
        <ul>
    <div>
<div>
@stop
