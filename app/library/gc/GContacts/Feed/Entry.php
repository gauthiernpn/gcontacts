<?php


namespace GContacts\Feed;


class Entry
{
    protected $_xml;

    /** @var \DomDocument */
    protected $_dom;

//    protected $_basicFields = ['id', 'updated', 'title', 'content'];
//    protected $_fields = ['link', 'category'];
//    protected $_simpleGoogleFields = ['email', 'im', 'phoneNumber'];
//    protected $_extendedGoogleFields = ['name', 'structuredPostalAddress'];

    // new holders for fields:
    public $id;
    public $updated;
    public $title;
    public $content;

    public $shortID;
    public $etag;

    public $link = [];
    public $category = [];
    public $email = [];
    public $im = [];
    public $phoneNumber = [];
    public $name = [];
    public $structuredPostalAddress = [];
    public $organization = [];


    public function __construct($xml = null)
    {
        if (!is_null($xml)) {
            $this->_xml = $xml;
            $this->_dom = new \DOMDocument();
            $this->_dom->formatOutput = true;
            $this->_dom->loadXML($this->_xml);
        }
    }

    public function getNode()
    {
        return $this->_dom;
    }

    /**
     * @param \GContacts\AtomType\Category $category
     */
    public function setCategory(\GContacts\AtomType\Category $category)
    {
        $this->category = $category;
    }

    /**
     * @return \GContacts\AtomType\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param array $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return array
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param array $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return array
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param \GContacts\AtomType\Name $name
     */
    public function setName(\GContacts\AtomType\Name $name)
    {
        $this->name = $name;
    }

    /**
     * @return \GContacts\AtomType\Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $im
     */
    public function setIm($im)
    {
        $this->im = $im;
    }

    /**
     * @return array
     */
    public function getIm()
    {
        return $this->im;
    }

    /**
     * @param array $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return array
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param array $structuredPostalAddress
     */
    public function setStructuredPostalAddress($structuredPostalAddress)
    {
        $this->structuredPostalAddress = $structuredPostalAddress;
    }

    /**
     * @return array
     */
    public function getStructuredPostalAddress()
    {
        return $this->structuredPostalAddress;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $shortID
     */
    public function setShortID($shortID)
    {
        $this->shortID = $shortID;
    }

    /**
     * @return mixed
     */
    public function getShortID()
    {
        return $this->shortID;
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
     * @param array $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return array
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param \DomDocument $dom
     */
    public function setDom($dom)
    {
        $this->_dom = $dom;
    }

    /**
     * @return \DomDocument
     */
    public function getDom()
    {
        return $this->_dom;
    }


} 