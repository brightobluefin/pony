@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h4>Objects</h4>
        <ul class="list-group">
            <li class="list-group-item">Campaigns
              <a href="/{{$id}}/campaign/delete" class="btn btn-default pull-right">Delete </a>
              <a href="/{{$id}}/campaign/sync" class="btn btn-default pull-right">Sync </a>
            </li>
            <li class="list-group-item">Ad Groups
              <a href="/{{$id}}/adgroup/delete" class="btn btn-default pull-right">Delete </a>
              <a href="/{{$id}}/adgroup/sync" class="btn btn-default pull-right">Sync </a>
            </li>
            <li class="list-group-item">Ads
              <a href="/{{$id}}/ad/delete" class="btn btn-default pull-right">Delete </a>
              <a href="/{{$id}}/ad/sync" class="btn btn-default pull-right">Sync </a>
            </li>
            <li class="list-group-item">Keywords
              <a href="/{{$id}}/keyword/delete" class="btn btn-default pull-right">Delete </a>
              <a href="/{{$id}}/keyword/sync" class="btn btn-default pull-right">Sync </a>
            </li>
            <li class="list-group-item">Ad Extension
                <a href="/{{$id}}/adextension/delete" class="btn btn-default pull-right">Delete </a>
                <a href="/{{$id}}/adextension/sync" class="btn btn-default pull-right">Sync </a>
            </li>
        <ul>
    <div>
<div>
@stop
