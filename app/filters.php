<?php

use Carbon\Carbon as Carbon;

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(
    function ($request) {
        //
    }
);


App::after(
    function ($request, $response) {
        //
    }
);

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter(
    'auth', function () {
        if (Auth::guest()) {
            if (Request::ajax()) {
                return Response::make('Unauthorized', 401);
            } else {
                return Redirect::guest('login');
            }
        }
    }
);

//Route::filter(
//    'auth.google', function () {
//        $hasToken = Session::has('token');
//        $hasTime = Session::has('time');
//        if (!$hasTime && !$hasToken) {
//            return Redirect::route('index');
//        } else {
//            $time = Session::get('time');
//            $now = new Carbon;
//            if($now > $time) {
//                Session::forget('token');
//                Session::forget('time');
//                return Redirect::route('index');
//            }
//        }
//    }
//);

Route::filter(
    'auth.google', function () {
        if (Session::has('access_token')) {
            $token = Session::get('access_token');
            $expireMoment = $token->created + $token->expires_in;
            $time = time();
            if ($time > $expireMoment) {
                Session::forget('access_token');
                return Redirect::route('index');
            }
        } else {
            return Redirect::route('index');
        }

    }
);

Route::filter(
    'auth.google.reversed', function () {
        if (Session::has('access_token')) {
            $token = Session::get('access_token');
            $expireMoment = $token->created + $token->expires_in;
            $time = time();
            if ($time <= $expireMoment) {
                return Redirect::route('home');
            }
        }

    }
);


Route::filter(
    'auth.basic', function () {
        return Auth::basic();
    }
);

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter(
    'guest', function () {
        if (Auth::check()) {
            return Redirect::to('/');
        }
    }
);

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter(
    'csrf', function () {
        if (Session::token() != Input::get('_token')) {
            throw new Illuminate\Session\TokenMismatchException;
        }
    }
);
