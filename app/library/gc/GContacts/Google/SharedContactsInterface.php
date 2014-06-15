<?php


namespace GContacts\Google;


interface SharedContactsInterface
{

    public function all();

    public function logout();

    public function getContact($code);

    public function buildEntryFromArray($code, $arr);

    public function uploadPhoto($code, \Symfony\Component\HttpFoundation\File\UploadedFile $photo);

    public function update(\DomDocument $xml, $code);

    public function delete(\GContacts\Feed\Entry $contact, $code);

    public function create(\DomDocument $xml);

    public function getPhoto($code);

    public function getToken($tempToken);

} 