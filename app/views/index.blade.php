@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-lg-8 col-md-12 col-sm-12">
        <h1>Google Shared Contacts manager</h1>

        <p>
            This small tool allows you to manage the shared contacts in your Google Apps domain.
            You can read more about <a href="https://support.google.com/a/answer/60218?hl=en">shared contacts on this
                Google help page</a>.

            This tool does not save data locally. Any data received is only stored in your session
            and in the current request.
        </p>

        <p>
            To use this tool, make sure you are logged in and have Administrator rights on your
            Google Apps domain. This tool does not work for @gmail.com addresses!
        </p>

        <form class="form-horizontal" role="form" method="post" action="{{URL::Route('oauth.form-submit')}}">
            {{Form::token()}}
            <div class="form-group">
                <label for="domain" class="col-sm-4 control-label">Google Apps domain</label>

                <div class="col-sm-8">
                    <input type="text" class="form-control" id="domain" placeholder="example.com" name="domain">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <button type="submit" class="btn btn-info">Sign in using Google</button><br />
                    <span class="small">The page you will end up at will warn you about this tool being both
                    insecure and anonymous. Technically true, but unavoidable since the only way to access Shared
                    Contacts is through
                        <a href="https://developers.google.com/accounts/docs/AuthSub">a deprecated API</a>. Google
                    no longer offers the documentation to make this app more secure and less anonymous. Sorry!</span>
                </div>
            </div>
        </form>
        <p class="small text-danger">

        </p>

        <p class="small">
            The source code can be found on <a href="https://github.com/JC5/gcontacts">GitHub</a>. &copy; <a
                href="mailto:s@nder.be">Sander Dorigo</a>, {{date('Y')}}
        </p>

    </div>
</div>
@stop