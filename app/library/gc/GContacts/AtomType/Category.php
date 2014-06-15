<?php


namespace GContacts\AtomType;


class Category extends DefaultAtomType implements AtomEntryInterface
{
    public $scheme;
    public $term;

    public static function parseFromDomDocument(\DomDocument $dom)
    {
        $children = $dom->getElementsByTagName('category');
        $category = null;
        // always just one:
        if ($children->length == 1) {
            $first = $children->item(0);
            $category = new self;
            $scheme = $first->attributes->getNamedItem('scheme');
            $term = $first->attributes->getNamedItem('term');
            $category->setScheme($scheme->nodeValue);
            $category->setTerm($term->nodeValue);
        }
        return $category;
    }

    public static function parseFromArray($array)
    {
        $category = new self;
        $category->setScheme($array['scheme']);
        $category->setTerm($array['term']);
        return $category;
    }

    public function parseToDomNode(\DomDocument $dom)
    {
        return null;
    }

    /**
     * @param mixed $scheme
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @return mixed
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param mixed $term
     */
    public function setTerm($term)
    {
        $this->term = $term;
    }

    /**
     * @return mixed
     */
    public function getTerm()
    {
        return $this->term;
    }


}