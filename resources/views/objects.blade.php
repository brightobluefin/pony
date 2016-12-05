@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3><b>Account Name: {{$accountName}}</b></h3>
        <ul class="list-group">
            <li class="list-group-item">Campaigns
              <a href="/test/campaign/{{$id}}" class="btn btn-default pull-right">View </a>
              <a href="/{{$id}}/campaign/delete" onclick="return confirm('Are you sure you want to Delete?')" class="btn btn-default pull-right">Delete </a>
            </li>
            <li class="list-group-item">Ad Groups
              <a href="/test/adgroup/{{$id}}" class="btn btn-default pull-right">View </a>
              <a href="/{{$id}}/adgroup/delete" onclick="return confirm('Are you sure you want to Delete?')" class="btn btn-default pull-right">Delete </a>
            </li>
            <li class="list-group-item">Ads
              <a href="/test/ad/{{$id}}" class="btn btn-default pull-right">View </a>
              <a href="/{{$id}}/ad/delete" onclick="return confirm('Are you sure you want to Delete?')" class="btn btn-default pull-right">Delete </a>

            </li>
            <li class="list-group-item">Keywords
              <a href="/test/keyword/{{$id}}" class="btn btn-default pull-right">View </a>
              <a href="/{{$id}}/keyword/delete" onclick="return confirm('Are you sure you want to Delete?')" class="btn btn-default pull-right">Delete </a>
            </li>
            <li class="list-group-item">Ad Sitelink
                <a href="/test/sharedsitelink/{{$id}}" class="btn btn-default pull-right">View </a>
                <a href="/{{$id}}/sharedsitelink/delete" onclick="return confirm('Are you sure you want to Delete?')" class="btn btn-default pull-right">Delete </a>
            </li>
        <ul>
    <div>
<div>
@stop
