@extends('layouts.default')
@section('content')
<script type="text/javascript">
    var indexes = [];
</script>
    <div class="row">
        <div class="col-lg-12">
            <h1>GContacts // <a href="{{URL::Route('home')}}">{{{Session::get('hd')}}}</a> // Edit <span id="fullNameTitle">{{{$contact['name']['fullName']}}}</span></h1>
            <p class="small" style="font-style: oblique;">
                If submitted changes don't seem to come through, refresh the page again.
            </p>
        </div>
    </div>
    {{Form::open(['files' => true])}}
    {{Form::hidden('etag',$contact['etag'])}}
    {{Form::hidden('title',$contact['title'])}}
    {{Form::hidden('id',$contact['shortID'])}}
    {{Form::hidden('updated',$contact['updated'])}}
    {{Form::hidden('category[scheme]',$contact['category']['scheme'])}}
    {{Form::hidden('category[term]',$contact['category']['term'])}}
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
                    indexes['organization'] = {{count($contact['organization'])}};
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
                    indexes['phoneNumber'] = {{count($contact['phoneNumber'])}};
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
                    indexes['im'] = {{count($contact['im'])}};
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
                    indexes['email'] = {{count($contact['email'])}};
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
                    indexes['structuredPostalAddress'] = {{count($contact['structuredPostalAddress'])}};
                </script>
                <a href="#" rel="structuredPostalAddress" class="addRow btn btn-default btn-sm">Add another address</a>
            </p>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <p>
                <input type="submit" class="btn btn-primary btn-lg" value="Submit changes." />
            </p>
            <p class="text-warning">
                Be patient when submitting. There is an intentional delay built in to make sure
                your updates are reflected back on this page when querying Google. However, often Google
                lingers so your updates might not show immediately.
            </p>
        </div>
    </div>
    {{Form::close()}}
@stop
@section('scripts')
<script type="text/javascript">
    var code ="{{{$code}}}";
</script>
<script src="assets/js/contacts.edit.js"></script>
@stop