<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

    public function __construct(GContacts\Google\SharedContactsInterface $contacts)
    {
        $this->contacts = $contacts;
    }

	public function index()
	{
        return View::make('index');
	}
    public function privacy()
    {
        return View::make('privacy');
    }

    public function home() {
        $list = $this->contacts->all();
        if(!is_array($list)) {
            return View::make('error')->with('message',$list);
        }
        return View::make('home')->with('contacts',$list);
    }
}
