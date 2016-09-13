@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h4>Sync</h4>
        <form  action="/cards/{{$card->id}}/notes" method="post">
        {{csrf_field()}}

        <input type="hidden" name="user_id" value="1">

        <div class="form-group">
            <textarea name="body" class="form-control">{{old('body')}}</textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn bhtn-primary" >Sync</button>
        </div>

      </form>
    <div>
<div>
@stop
