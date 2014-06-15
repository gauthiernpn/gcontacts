<?php


namespace GContacts\AtomType;


class Name extends DefaultAtomType implements AtomEntryInterface
{

    public $givenName;
    public $additionalName;
    public $familyName;
    public $namePrefix;
    public $nameSuffix;
    public $fullName;

    public function __construct()
    {
    }

    public static function parseFromDomDocument(\DomDocument $doc)
    {
        $children = $doc->getElementsByTagNameNS(self::$GD, 'name');
        $name = null;
        // always just one:
        if ($children->length == 1) {
            $first = $children->item(0);
            $name = new self;
            $fields = ['givenName', 'additionalName', 'familyName', 'namePrefix', 'nameSuffix', 'fullName'];
            /** @var $childNode \DomNode */
            foreach ($first->childNodes as $childNode) {
                $nodeName = str_replace('gd:', '', $childNode->nodeName);
                if (in_array($nodeName, $fields)) {
                    $fn = 'set' . ucfirst($nodeName);
                    $name->$fn($childNode->nodeValue);
                }
            }
        }
        return $name;
    }

    public function parseToDomNode(\DomDocument $dom)
    {
        $name = $dom->createElement('gd:name');
        $fullName = $dom->createElement('gd:fullName', $this->getFullName());
        $name->appendChild($fullName);
        if (strlen($this->getGivenName()) > 0) {
            $name->appendChild($dom->createElement('gd:givenName', $this->getGivenName()));
        }
        if (strlen($this->getAdditionalName()) > 0) {
            $name->appendChild($dom->createElement('gd:additionalName', $this->getAdditionalName()));
        }
        if (strlen($this->getFamilyName()) > 0) {
            $name->appendChild($dom->createElement('gd:familyName', $this->getFamilyName()));
        }
        if (strlen($this->getNamePrefix()) > 0) {
            $name->appendChild($dom->createElement('gd:namePrefix', $this->getNamePrefix()));
        }
        if (strlen($this->getNameSuffix()) > 0) {
            $name->appendChild($dom->createElement('gd:nameSuffix', $this->getNameSuffix()));
        }
        $dom->appendChild($name);
        return $name;

    }

    public static function parseFromArray($array)
    {
        $name = new self;
        $name->setAdditionalName($array['additionalName']);
        $name->setFamilyName($array['familyName']);
        $name->setGivenName($array['givenName']);
        $name->setNamePrefix($array['namePrefix']);
        $name->setNameSuffix($array['nameSuffix']);
        $name->generateFullName();
        unset($tmp);
        return $name;
    }

    public function generateFullName()
    {
        $tmp = [$this->getNamePrefix(), $this->getGivenName(), $this->getAdditionalName(), $this->getFamilyName(),
                $this->getNameSuffix()];
        $tmp = array_filter($tmp);
        $this->setFullName(join(' ', $tmp));
    }

    /**
     * @param mixed $additionalName
     */
    public function setAdditionalName($additionalName)
    {
        $this->additionalName = $additionalName;
    }

    /**
     * @return mixed
     */
    public function getAdditionalName()
    {
        return $this->additionalName;
    }

    /**
     * @param mixed $familyName
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;
    }

    /**
     * @return mixed
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $givenName
     */
    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;
    }

    /**
     * @return mixed
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * @param mixed $namePrefix
     */
    public function setNamePrefix($namePrefix)
    {
        $this->namePrefix = $namePrefix;
    }

    /**
     * @return mixed
     */
    public function getNamePrefix()
    {
        return $this->namePrefix;
    }

    /**
     * @param mixed $nameSuffix
     */
    public function setNameSuffix($nameSuffix)
    {
        $this->nameSuffix = $nameSuffix;
    }

    /**
     * @return mixed
     */
    public function getNameSuffix()
    {
        return $this->nameSuffix;
    }


}