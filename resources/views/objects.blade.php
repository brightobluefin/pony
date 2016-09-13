@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h4>Objects</h4>
        <ul class="list-group">
            <li class="list-group-item">Campaigns
              <a href="/{{$id}}/campaign/sync" class="btn btn-default pull-right">Sync </a>
            </li>
            <li class="list-group-item">Ad groups
              <a href="/{{$id}}/adgroup/sync" class="btn btn-default pull-right">Sync </a>
            </li>
            <li class="list-group-item">Ads
              <a href="/{{$id}}/ads/sync" class="btn btn-default pull-right">Sync </a>
            </li>
            <li class="list-group-item">Keywords
              <a href="/{{$id}}/keywords/sync" class="btn btn-default pull-right">Sync </a>
            </li>
        <ul>
    <div>
<div>
@stop
