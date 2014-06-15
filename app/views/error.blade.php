@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-danger">Error</h1>
            <p>
                An error occurred while executing this action:
            </p>
            <p class="text-danger">
                {{$message}}
            </p>
            <p>
                <div class="btn-group">
                <a href="{{URL::Route('oauth.logout')}}" class="btn btn-danger">Logout</a>
                <a href="{{URL::Route('home')}}" class="btn btn-default">Home</a>
                </div>

            </p>
        </div>
    </div>
@stop