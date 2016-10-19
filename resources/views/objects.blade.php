@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h4>Objects</h4>
        <ul class="list-group">
            <li class="list-group-item">Campaigns
              <a href="/test/campaign/{{$id}}" class="btn btn-default pull-right">View </a>
              <a href="/{{$id}}/campaign/delete" class="btn btn-default pull-right">Delete </a>
            </li>
            <li class="list-group-item">Ad Groups
              <a href="/test/adgroup/{{$id}}" class="btn btn-default pull-right">View </a>
              <a href="/{{$id}}/adgroup/delete" class="btn btn-default pull-right">Delete </a>
            </li>
            <li class="list-group-item">Ads
              <a href="/test/ad/{{$id}}" class="btn btn-default pull-right">View </a>
              <a href="/{{$id}}/ad/delete" class="btn btn-default pull-right">Delete </a>

            </li>
            <li class="list-group-item">Keywords
              <a href="/test/keyword/{{$id}}" class="btn btn-default pull-right">View </a>
              <a href="/{{$id}}/keyword/delete" class="btn btn-default pull-right">Delete </a>
            </li>
            <li class="list-group-item">Ad Extension
                <a href="/test/adextension/{{$id}}" class="btn btn-default pull-right">View </a>
                <a href="/{{$id}}/adextension/delete" class="btn btn-default pull-right">Delete </a>
            </li>
        <ul>
    <div>
<div>
@stop
