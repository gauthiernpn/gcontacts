<?php
namespace GContacts\AtomType;

class Im extends DefaultAtomType implements AtomEntryInterface
{
    // attributes:
    public $address;
    public $label;
    public $rel;
    public $protocol;
    public $primary;

    protected $rels;
    protected $protocols;

    public function __construct()
    {
        $this->rels = self::getDefaultRels();
        $this->protocols = self::getDefaultProtocols();

    }

    public static function getDefaultRels()
    {
        return [
            'http://schemas.google.com/g/2005#home'       => 'Home',
            'http://schemas.google.com/g/2005#netmeeting' => 'Netmeeting',
            'http://schemas.google.com/g/2005#other'      => 'Other',
            'http://schemas.google.com/g/2005#work'       => 'Work',
            ''                                            => null
        ];

    }

    public static function getDefaultProtocols()
    {
        return ['http://schemas.google.com/g/2005#AIM'         => 'AIM',
                'http://schemas.google.com/g/2005#MSN'         => 'MSN',
                'http://schemas.google.com/g/2005#YAHOO'       => 'Yahoo!',
                'http://schemas.google.com/g/2005#SKYPE'       => 'Skype',
                'http://schemas.google.com/g/2005#QQ'          => 'QQ',
                'http://schemas.google.com/g/2005#GOOGLE_TALK' => 'Google Talk',
                'http://schemas.google.com/g/2005#ICQ'         => 'ICQ',
                'http://schemas.google.com/g/2005#JABBER'      => 'Jabber',
                ''                                             => null,
        ];

    }

    public static function parseFromDomDocument(\DomDocument $doc)
    {
        $list = $doc->getElementsByTagNameNS(self::$GD, 'im');
        $ims = [];
        if ($list->length > 0) {
            /** @var $node \DomNode */
            foreach ($list as $node) {
                $im = new \GContacts\AtomType\Im;
                $var = $node->attributes->getNamedItem('address');
                $im->setAddress($var ? $var->nodeValue : null);

                $var = $node->attributes->getNamedItem('label');
                $im->setLabel($var ? $var->nodeValue : null);

                $var = $node->attributes->getNamedItem('rel');
                $im->setRel($var ? $im->rels[$var->nodeValue] : null);

                $var = $node->attributes->getNamedItem('protocol');
                $im->setProtocol($var ? $im->protocols[$var->nodeValue] : null);

                $var = $node->attributes->getNamedItem('primary');
                $im->setPrimary($var ? $var->nodeValue : null);

                $ims[] = $im;
            }
        }
        return $ims;
    }

    public static function parseFromArray($array)
    {
        $im = new self;
        $im->setAddress($array['address']);
        $im->setLabel($array['label']);
        $im->setPrimary(isset($array['primary']) ? $array['primary'] : false);
        $im->setRel($array['rel']);
        $im->setProtocol($array['protocol']);
        return $im;
    }

    public function parseToDomNode(\DomDocument $dom)
    {
        $instantMessaging = $dom->createElement('gd:im');
        foreach (\GContacts\AtomType\Im::getDefaultRels() as $key => $rel) {
            if ($rel == $this->getRel()) {
                $instantMessaging->setAttribute('rel', $key);
            }
        }
        foreach (\GContacts\AtomType\Im::getDefaultProtocols() as $key => $protocol) {
            if ($protocol == $this->getProtocol()) {
                $instantMessaging->setAttribute('protocol', $key);
            }
        }
        if ($this->isPrimary()) {
            $instantMessaging->setAttribute('primary', 'true');
        }
        $instantMessaging->setAttribute('address', $this->getAddress());
        $instantMessaging->setAttribute('label', $this->getLabel());
        return $instantMessaging;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $primary
     */
    public function setPrimary($primary)
    {
        $this->primary = $primary;
    }

    /**
     * @return mixed
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * @param mixed $protocol
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->protocol;
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
     * @return bool
     */
    public function isPrimary()
    {
        return $this->primary == 'true' || $this->primary == '1';
    }

}