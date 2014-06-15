<?php


namespace GContacts\Feed;


class EntryParser
{

    public static $GD = 'http://schemas.google.com/g/2005';

    public static function parseFromXML($xml)
    {
        $DOM = new \DOMDocument();
        $DOM->formatOutput = true;
        $DOM->loadXML($xml);
        return self::parseFromDomDocument($DOM);

    }

    public static function parseFromDomDocument(\DomDocument $DOM)
    {
        $contact = new Entry;

        // we parse the basic fields ourselves:
        foreach (['id', 'updated', 'title', 'content'] as $field) {
            $fn = 'set' . ucfirst($field);
            $nodeList = $DOM->getElementsByTagName($field);
            if ($nodeList->length == 1) {
                $contact->$fn($nodeList->item(0)->textContent);
            }
        }

        // we parse the shortID ourselves as well:
        $search = 'http://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/base/';
        $shortID = str_replace($search, '', $contact->getId());
        $contact->setShortID($shortID);
        unset($search, $shortID);

        // etag too:
        $etag = null;
        $etagNodes = $DOM->getElementsByTagName('entry');
        if ($etagNodes->length == 1) {
            $attributes = $etagNodes->item(0)->attributes;
            if ($attributes->length > 0) {
                $etagNode = $attributes->getNamedItemNS(EntryParser::$GD, 'etag');
                $etag = $etagNode->nodeValue;
            }
        }
        $contact->setEtag($etag);
        unset($etag, $etagNode, $etagNodes, $attributes);

        // we leave the rest to the classes:
        $link = \GContacts\AtomType\Link::parseFromDomDocument($DOM);
        $contact->setLink($link);

        $category = \GContacts\AtomType\Category::parseFromDomDocument($DOM);
        $contact->setCategory($category);

        $email = \GContacts\AtomType\Email::parseFromDomDocument($DOM);
        $contact->setEmail($email);

        $im = \GContacts\AtomType\Im::parseFromDomDocument($DOM);
        $contact->setIm($im);

        $phone = \GContacts\AtomType\Phonenumber::parseFromDomDocument($DOM);
        $contact->setPhoneNumber($phone);

        $name = \GContacts\AtomType\Name::parseFromDomDocument($DOM);
        $contact->setName($name);

        $organization = \GContacts\AtomType\Organization::parseFromDomDocument($DOM);
        $contact->setOrganization($organization);

        $spa = \GContacts\AtomType\StructuredPostalAddress::parseFromDomDocument($DOM);
        $contact->setStructuredPostalAddress($spa);

        return $contact;

    }

    public static function parseFromArray($array)
    {
        $contact = new Entry;

        foreach (['etag', 'content', 'id', 'updated'] as $field) {
            $fn = 'set' . ucfirst($field);
            if (isset($array[$field]) && method_exists($contact, $fn)) {
                $contact->$fn($array[$field]);
            }
        }

        // create category:
        if (isset($array['category'])) {
            $category = \GContacts\AtomType\Category::parseFromArray($array['category']);
        } else {
            $category = new \GContacts\AtomType\Category;
            $category->setScheme('http://schemas.google.com/g/2005#kind');
            $category->setTerm('http://schemas.google.com/contact/2008#contact');
        }
        $contact->setCategory($category);

        // name:
        $name = \GContacts\AtomType\Name::parseFromArray($array);
        $contact->setName($name);

        // phone numbers
        $phones = [];
        if (isset($array['phone'])) {
            foreach ($array['phone'] as $index => $phone) {
                $ph = \GContacts\AtomType\Phonenumber::parseFromArray($phone);
                if (isset($array['phoneprimary']) && intval($array['phoneprimary']) == $index) {
                    $ph->setPrimary('true');
                }
                $phones[] = $ph;
            }
        }
        $contact->setPhoneNumber($phones);

        // emails
        $emails = [];
        if (isset($array['email'])) {
            foreach ($array['email'] as $index => $email) {
                $em = \GContacts\AtomType\Email::parseFromArray($email);
                if (isset($array['emailprimary']) && intval($array['emailprimary']) == $index) {
                    $em->setPrimary('true');
                }
                $emails[] = $em;
            }
        }
        $contact->setEmail($emails);

        // ims
        $ims = [];
        if (isset($array['im'])) {
            foreach ($array['im'] as $im) {
                $newIm = \GContacts\AtomType\Im::parseFromArray($im);
                $ims[] = $newIm;
            }
        }
        $contact->setIm($ims);

        // addresses
        $addresses = [];
        if (isset($array['address'])) {
            foreach ($array['address'] as $address) {
                $addr = \GContacts\AtomType\StructuredPostalAddress::parseFromArray($address);
                $addresses[] = $addr;
            }
        }
        $contact->setStructuredPostalAddress($addresses);

        // organizations:
        $organizations = [];
        if (isset($array['organization'])) {
            foreach ($array['organization'] as $index => $org) {
                $organization = \GContacts\AtomType\Organization::parseFromArray($org);

                if (isset($array['orgprimary']) && intval($array['orgprimary']) == $index) {
                    $organization->setPrimary('true');
                }

                $organizations[] = $organization;
            }
        }
        $contact->setOrganization($organizations);

        return $contact;

    }

    public static function parseToArray(Entry $contact)
    {
        $array = [
            'etag'                    => $contact->getEtag(),
            'content'                 => $contact->getContent(),
            'id'                      => $contact->getId(),
            'shortID'                 => $contact->getShortID(),
            'updated'                 => $contact->getUpdated(),
            'title'                   => $contact->getTitle(),
            'category'                => [
                'scheme' => $contact->getCategory()->getScheme(),
                'term'   => $contact->getCategory()->getTerm(),
            ],

            'name'                    => [
                'fullName'       => $contact->getName()->getFullName(),
                'givenName'      => $contact->getName()->getGivenName(),
                'familyName'     => $contact->getName()->getFamilyName(),
                'additionalName' => $contact->getName()->getAdditionalName(),
                'namePrefix'     => $contact->getName()->getNamePrefix(),
                'nameSuffix'     => $contact->getName()->getNameSuffix(),
            ],
            'phoneNumber'             => [],
            'email'                   => [],
            'im'                      => [],
            'structuredPostalAddress' => [],
            'organization'            => []
        ];
        /** @var $phone \GContacts\AtomType\Phonenumber */
        foreach ($contact->getPhoneNumber() as $phone) {
            $array['phoneNumber'][] = [
                'number'  => $phone->getNumber(),
                'rel'     => $phone->getRel(),
                'uri'     => $phone->getUri(),
                'label'   => $phone->getLabel(),
                'primary' => $phone->isPrimary()
            ];
        }

        /** @var $email \GContacts\AtomType\Email */
        foreach ($contact->getEmail() as $email) {
            $array['email'][] = [
                'address' => $email->getAddress(),
                'rel'     => $email->getRel(),
                'label'   => $email->getLabel(),
                'primary' => $email->isPrimary()
            ];
        }

        /** @var $im \GContacts\AtomType\Im */
        foreach ($contact->getIm() as $im) {
            $array['im'][] = [
                'address'  => $im->getAddress(),
                'rel'      => $im->getRel(),
                'protocol' => $im->getProtocol(),
                'label'    => $im->getLabel(),
                'primary'  => $im->isPrimary()
            ];
        }

        /** @var $org \GContacts\AtomType\Organization */
        foreach ($contact->getOrganization() as $org) {
            $array['organization'][] = [
                'rel'               => $org->getRel(),
                'label'             => $org->getLabel(),
                'primary'           => $org->isPrimary(),
                'orgDepartment'     => $org->getOrgDepartment(),
                'orgJobDescription' => $org->getOrgJobDescription(),
                'orgName'           => $org->getOrgName(),
                'orgSymbol'         => $org->getOrgSymbol(),
                'orgTitle'          => $org->getOrgTitle(),
                'where'             => $org->getWhere()
            ];
        }

        /** @var $spa \GContacts\AtomType\StructuredPostalAddress */
        foreach ($contact->getStructuredPostalAddress() as $spa) {
            $array['structuredPostalAddress'][] = [
                'rel'              => $spa->getRel(),
                'mailClass'        => $spa->getMailClass(),
                'usage'            => $spa->getUsage(),
                'label'            => $spa->getLabel(),
                'primary'          => $spa->isPrimary(),
                'agent'            => $spa->getAgent(),
                'housename'        => $spa->getHousename(),
                'street'           => $spa->getStreet(),
                'pobox'            => $spa->getPobox(),
                'neighborhood'     => $spa->getNeighborhood(),
                'city'             => $spa->getCity(),
                'subregion'        => $spa->getSubregion(),
                'region'           => $spa->getRegion(),
                'postcode'         => $spa->getPostcode(),
                'country'          => $spa->getCountry(),
                'formattedAddress' => $spa->getFormattedAddress(),
            ];
        }

        return $array;

    }

    public static function parseToXML(Entry $contact)
    {
        // make DomDocument:
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        // create ROOT
        $entry = $dom->createElement('entry');
        $entry->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.w3.org/2005/Atom');
        $entry->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:gd', self::$GD);
        $entry->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:app', 'http://www.w3.org/2007/app');
        $entry->setAttribute('gd:etag', $contact->getEtag());
        $dom->appendChild($entry);

        // add ID:
        $id = $dom->createElement(
            'id', 'http://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/base/' . $contact->getId()
        );
        $entry->appendChild($id);

        // add date:
        $updated = $dom->createElement('updated', date('Y-m-d\TH:i:s.000\Z'));
        $entry->appendChild($updated);

        // add 'edited'
        $edited = $dom->createElementNS('http://www.w3.org/2007/app', 'edited', date('Y-m-d\TH:i:s.000\Z'));
        $edited->setAttribute('xmlns:app', 'http://www.w3.org/2007/app');
        $entry->appendChild($edited);

        // add category:
        $category = $dom->createElement('category');
        $category->setAttribute('scheme', $contact->getCategory()->getScheme());
        $category->setAttribute('term', $contact->getCategory()->getTerm());
        $entry->appendChild($category);

        $title = $dom->createElement('title', $contact->getName()->getFullName());
        $entry->appendChild($title);

        // add the notes:
        $content = $dom->createElement('content', $contact->getContent());
        $entry->appendChild($content);

        // build the name
        $entry->appendChild($contact->getName()->parseToDomNode($dom));

        // add email:
        /** @var $email \GContacts\AtomType\Email */
        foreach ($contact->getEmail() as $email) {
            $entry->appendChild($email->parseToDomNode($dom));
        }

        // add im
        /** @var $im \GContacts\AtomType\Im */
        foreach ($contact->getIm() as $im) {
            $entry->appendChild($im->parseToDomNode($dom));
        }

        // add phone
        /** @var $p \GContacts\AtomType\Phonenumber */
        foreach ($contact->getPhoneNumber() as $p) {
            $entry->appendChild($p->parseToDomNode($dom));
        }

        // add address
        /** @var $a \GContacts\AtomType\StructuredPostalAddress */
        foreach ($contact->getStructuredPostalAddress() as $a) {
            $entry->appendChild($a->parseToDomNode($dom));
        }

        // add org
        /** @var $org \GContacts\AtomType\Organization */
        foreach ($contact->getOrganization() as $org) {
            $entry->appendChild($org->parseToDomNode($dom));
        }
        return $dom;
    }


} 