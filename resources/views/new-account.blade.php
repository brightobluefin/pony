@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3>Create New SandBox Account</h3>
        <form class="form-horizontal" role="form" method="POST" action="/save-account">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="account-name" class="col-md-4 control-label">Account Name</label>

                <div class="col-md-6">
                    <input id="account-name" type="name" class="form-control" name="account-name" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </div>
        </form>
    <div>
<div>
@stop
