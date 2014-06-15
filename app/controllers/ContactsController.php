<?php


class ContactsController extends BaseController
{

    public function __construct(GContacts\Google\SharedContactsInterface $contacts)
    {
        $this->contacts = $contacts;
    }

    public function add()
    {
        return View::make('add');
    }

    public function delete($code)
    {
        $contact = $this->contacts->getContact($code);
        return View::make('delete')->with('contact', $contact);
    }

    public function postDelete($code)
    {

        $contact = $this->contacts->getContact($code);
        $result = $this->contacts->delete($contact, $code);
        if ($result === true) {
            return Redirect::route('home');
        } else {
            // $error contains the error message!
            return View::make('error')->with('message', $result);
        }

    }

    public function edit($code)
    {

        $contact = $this->contacts->getContact($code);

        if (is_string($contact)) {
            return View::make('error')->with('message', $contact);
        } else {
            $contactArray = \GContacts\Feed\EntryParser::parseToArray($contact);
            return View::make('edit')->with('code', $code)->with('contact', $contactArray)->with(
                'fullContact', $contact
            );
        }
    }

    public function postAdd()
    {
        $contact = \GContacts\Feed\EntryParser::parseFromArray(Input::all());

        $contactXML = \GContacts\Feed\EntryParser::parseToXML($contact);

        $result = $this->contacts->create($contactXML);
        if ($result === true) {
            return Redirect::route('home');
        } else {
            return View::make('error')->with('message',$result);
        }
    }

    public function postEdit($code)
    {

        if (Input::hasFile('photo')) {
            $this->contacts->uploadPhoto($code, Input::file('photo'));
        }
        $contact = \GContacts\Feed\EntryParser::parseFromArray(Input::all());

        $contactXML = \GContacts\Feed\EntryParser::parseToXML($contact);
        $result = $this->contacts->update($contactXML, $code);

        if ($result === true) {
            return Redirect::route('contacts.edit', [$code]);
        } else {
            return View::make('error')->with('message',$result);
        }
    }

    public function editPhoto() {
        return 'todo, sorry.';
    }


}