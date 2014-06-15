@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1>GContacts // <a href="{{URL::Route('home')}}">{{{Session::get('hd')}}}</a> // Delete {{{$contact->getName()->getFullName()}}}</h1>
        </div>
    </div>
    {{Form::open(['files' => true])}}
    <div class="row">

        <div class="col-lg-12">
            <p>If you are sure you want to delete {{{$contact->getName()->getFullName()}}}, press Yes:</p>
            <p><input type="submit" class="btn btn-danger btn-lg" name="delete" value="Yes, delete {{{$contact->getName()->getFullName()}}}" /></p>
        </div>
    </div>
    {{Form::close()}}
@stop