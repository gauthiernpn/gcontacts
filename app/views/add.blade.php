@extends('layouts.default')
@section('content')
<script type="text/javascript">
    var indexes = [];
</script>
    <div class="row">
        <div class="col-lg-12">
            <h1>GContacts // <a href="{{URL::Route('home')}}">{{{Session::get('hd')}}}</a> // Add new contact</h1>
        </div>
    </div>
    {{Form::open(['files' => true])}}
    <div class="row">

        <div class="col-lg-6">
            <h3>Name</h3>
            @include('edit/name')
        </div>
        <div class="col-lg-6">
            <h3>Photo</h3>
            @include('edit/photo')
            <h3>Notes</h3>
            @include('edit/notes')
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <h3>Organization</h3>
            <div id="organization">
                @include('edit/organization')
            </div>
            <p>
                <script type="text/javascript">
                    indexes['organization'] = 0;
                </script>
                <a href="#" rel="organization" class="addRow btn btn-default btn-sm">Add another organization</a>
            </p>
        </div>
        <div class="col-lg-6">
            <h3>Phone numbers</h3>
            <div id="phoneNumber">
                @include('edit/phone')
            </div>
            <p>
                <script type="text/javascript">
                    indexes['phoneNumber'] = 0;
                </script>
                <a href="#" rel="phoneNumber" class="addRow btn btn-default btn-sm">Add another phone number</a>
            </p>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-6">
            <h3>Instant messaging</h3>
            <div id="im">
                @include('edit/im')
            </div>
            <p>
                <script type="text/javascript">
                    indexes['im'] = 0;
                </script>
                <a href="#" rel="im" class="addRow btn btn-default btn-sm">Add another IM</a>
            </p>
        </div>
        <div class="col-lg-6">
            <h3>Email addresses</h3>
            <div id="email">
                @include('edit/email')
            </div>
            <p>
                <script type="text/javascript">
                    indexes['email'] = 0;
                </script>
                <a href="#" rel="email" class="addRow btn btn-default btn-sm">Add another email address</a>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <h3>Addresses</h3>
            <div id="structuredPostalAddress">
                @include('edit/address')
            </div>
            <p>
                <script type="text/javascript">
                    indexes['structuredPostalAddress'] = 0;
                </script>
                <a href="#" rel="structuredPostalAddress" class="addRow btn btn-default btn-sm">Add another address</a>
            </p>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <input type="submit" class="btn btn-primary btn-lg" value="Submit changes." />
        </div>
    </div>
    {{Form::close()}}
@stop
@section('scripts')
<script src="assets/js/contacts.edit.js"></script>
@stop