<?php


namespace GContacts\AtomType;


class Email extends DefaultAtomType implements AtomEntryInterface
{

    public $address;
    public $displayName;
    public $label;
    public $rel;
    public $primary;


    protected $rels;

    public function __construct()
    {
        $this->rels = self::getDefaultRels();
    }

    public static function getDefaultRels()
    {
        return [
            'http://schemas.google.com/g/2005#home'  => 'Home',
            'http://schemas.google.com/g/2005#other' => 'Other',
            'http://schemas.google.com/g/2005#work'  => 'Work',
        ];
    }

    public static function parseFromArray($array)
    {
        $email = new self;
        $email->setLabel($array['label']);
        $email->setRel($array['rel']);
        $email->setPrimary(isset($array['primary']) ? $array['primary'] : false);
        $email->setAddress($array['address']);
        //$email->setDisplayName($array['displayName']);
        return $email;
    }

    public function parseToDomNode(\DomDocument $dom)
    {
        $emailAddress = $dom->createElement('gd:email');

        if ($this->isPrimary()) {
            $emailAddress->setAttribute('primary', 'true');
        }
        $emailAddress->setAttribute('address', $this->getAddress());
        if (strlen($this->getLabel()) > 0) {
            $emailAddress->setAttribute('label', $this->getLabel());
        } else {
            foreach (self::getDefaultRels() as $key => $rel) {
                if ($rel == $this->getRel()) {
                    $emailAddress->setAttribute('rel', $key);
                }
            }
        }

        return $emailAddress;
    }

    public static function parseFromDomDocument(\DomDocument $doc)
    {
        $list = $doc->getElementsByTagNameNS(self::$GD, 'email');
        $emails = [];
        if ($list->length > 0) {
            /** @var $node \DomNode */
            foreach ($list as $node) {
                $email = new \GContacts\AtomType\Email;
                $var = $node->attributes->getNamedItem('address');
                $email->setAddress($var ? $var->nodeValue : null);

                $var = $node->attributes->getNamedItem('displayName');
                $email->setDisplayName($var ? $var->nodeValue : null);

                $var = $node->attributes->getNamedItem('label');
                $email->setLabel($var ? $var->nodeValue : null);

                $var = $node->attributes->getNamedItem('rel');
                $email->setRel($var ? $email->rels[$var->nodeValue] : null);

                $var = $node->attributes->getNamedItem('primary');
                $email->setPrimary($var ? $var->nodeValue : null);

                $emails[] = $email;
            }


        }
        return $emails;

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
     * @param mixed $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
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