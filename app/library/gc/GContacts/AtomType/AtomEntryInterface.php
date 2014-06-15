<?php


namespace GContacts\AtomType;


interface AtomEntryInterface
{
    public static function parseFromDomDocument(\DomDocument $doc);

    public static function parseFromArray($array);

    public function parseToDomNode(\DomDocument $dom);


} 