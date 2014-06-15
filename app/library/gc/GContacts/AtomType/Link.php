<?php


namespace GContacts\AtomType;


class Link extends DefaultAtomType
{
    public $rel;
    public $type;
    public $href;
    public $etag;
    public static $supported = ['rel', 'type', 'href', 'etag'];

    public static function parseFromDomDocument(\DomDocument $DOM)
    {
        $children = $DOM->getElementsByTagName('link');
        $links = [];
        /** @var $child \DomNode */
        foreach ($children as $child) {
            $link = new self;

            // rel from XML:
            foreach (self::$supported as $field) {
                $content = $child->attributes->getNamedItem($field);
                if (!is_null($content)) {
                    $fn = 'set' . ucfirst($field);
                    $link->$fn($content->nodeValue);
                }
            }
            $links[] = $link;
        }
        return $links;
    }

    public function parseToDomNode(\DomDocument $dom)
    {
        return null;
    }

    /**
     * @param mixed $etag
     */
    public function setEtag($etag)
    {
        $this->etag = $etag;
    }

    /**
     * @return mixed
     */
    public function getEtag()
    {
        return $this->etag;
    }

    /**
     * @param mixed $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    /**
     * @return mixed
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param mixed $rel
     */
    public function setRel($rel)
    {
        $this->rel = $rel;
    }

    /**
     * @return mixed
     */
    public function getRel()
    {
        return $this->rel;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }


}