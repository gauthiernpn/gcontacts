@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>GContacts // {{{Session::get('hd')}}}</h1>

        <p>
            These are the shared contacts for {{{Session::get('hd')}}}. They'll show up
            in lists called "Directory" or "Shared contacts" in your phone, in email applications
            and online.
        </p>

        <p>
            <a class="btn btn-default" href="{{URL::Route('contacts.add')}}"><span
                    class="glyphicon glyphicon-plus"></span> Add shared contact</a>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <table class="table" id="contactList">
            <tr>
                <th>Full name</th>
                <th>Email address(es)</th>
                <th>Phone number(s)</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            @foreach($contacts as $contact)
            <tr>
                <td>{{{$contact->getName()->getFullName()}}}</td>
                <td>
                    @if(count($contact->getEmail()) > 0)
                    <ul>
                        @foreach($contact->getEmail() as $email)
                        <li><a href="mailto:{{{$email->getAddress()}}}">{{{$email->getAddress()}}}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </td>
                <td>
                    @if(count($contact->getPhoneNumber()) > 0)
                    <ul>
                        @foreach($contact->getPhoneNumber() as $phone)
                        <li>{{{$phone->getNumber()}}}</li>
                        @endforeach
                    </ul>
                    @endif
                </td>
                <td>
                    <a href="{{URL::Route('contacts.edit',$contact->getShortId())}}" class="btn btn-info btn-xs"><span
                            class="glyphicon glyphicon-pencil"></span></a>
                </td>
                <td>
                    <a href="{{URL::Route('contacts.delete',$contact->getShortId())}}"
                       class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
                </td>
            </tr>

            @endforeach
        </table>
        <p class="text-info">
            Please remember it may take 24 hours for Google to reflect any changes made here.
        </p>

        <p><a href="{{URL::Route('oauth.logout')}}" class="btn btn-sm btn-danger">Disconnect</a></p>

    </div>
</div>
@stop