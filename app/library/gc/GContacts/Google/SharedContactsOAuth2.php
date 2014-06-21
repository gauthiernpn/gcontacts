<?php
namespace GContacts\Google;

class SharedContactsOAuth2 implements SharedContactsInterface
{

    public function all()
    {
        // this is where we start getting contacts:
        // we begin with nothing:
        $return = [];

        // we get a bunch of contacts in a loop:
        $moreContacts = true;
        $currentIndex = 1;
        \Log::debug('Start at index ' . $currentIndex);
        $loopCounter = 0;
        while ($moreContacts) {
            \Log::debug('Loopcounter: ' . $loopCounter);

            // get the nth set:
            $rawBody = $this->_getAllContacts($currentIndex);
            // if error, break out.
            if ($rawBody === false) {
                $moreContacts = false;
                break;
            }
            // and we parse the current set:
            $feed = \Zend\Feed\Reader\Reader::importString($rawBody);

            // and add them to the return list.
            if ($feed->count() > 0) {
                foreach ($feed as $entry) {
                    $current = \GContacts\Feed\EntryParser::parseFromXML($entry->saveXml());
                    $return[] = $current;
                }
            }

            // we figure out if there is more to come:
            $DOMDocument = new \DOMDocument();
            $DOMDocument->loadXML($rawBody);
            $nextLink = false;
            $moreContacts = false;
            /** @var $child \DomNode */
            foreach ($DOMDocument->getElementsByTagName('link') as $child) {
                $rel = $child->attributes->getNamedItem('rel')->nodeValue;
                if($rel == 'next') {
                    $moreContacts = true;
                    $currentIndex += $feed->count();
                }
            }
            $loopCounter++;
        }
        return $return;

    }

    private function _getAllContacts($index = 1)
    {
        $URL = 'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full';
        $URL .= '?max-results=25&start-index=' . $index;

        $token = \Session::get('access_token');
        $result = \Requests::get(
            $URL, [
                'Authorization' => 'Bearer ' . $token->access_token,
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'GData-Version' => '3.0',
            ]
        );

        if ($result->status_code == 200) {
            $rawBody = $result->body;
            return $rawBody;
        } else {
            return false;
        }
    }

    public function getContact($code)
    {
        $URL = 'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full/' . $code;
        $token = \Session::get('access_token');
        $result = \Requests::get(
            $URL, [
                'Authorization' => 'Bearer ' . $token->access_token,
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'GData-Version' => '3.0',
            ]
        );
        if ($result->status_code == 200) {
            $rawBody = $result->body;
            return \GContacts\Feed\EntryParser::parseFromXML($rawBody);
        } else {
            return 'Get contact with ID "' . $code . '" returned an error: ' . $result->status_code;
        }

    }

    public function buildEntryFromArray($code, $arr)
    {
        return \GContacts\Feed\Entry::buildEntryFromArray($code, $arr);
    }

    public function uploadPhoto($code, \Symfony\Component\HttpFoundation\File\UploadedFile $photo)
    {
        $URL = 'https://www.google.com/m8/feeds/photos/media/' . \Session::get('hd') . '/' . $code;
        $token = \Session::get('access_token');
        $mime = $photo->getClientMimeType();
        $result = \Requests::put(
            $URL, [
                'Authorization' => 'Bearer ' . $token->access_token,
                'Content-Type'  => $mime,
                'GData-Version' => '3.0',
                'If-Match'      => '*'
            ], file_get_contents($photo->getRealPath())
        );
    }

    public function update(\DomDocument $xml, $code)
    {
        $token = \Session::get('access_token');
        $result = \Requests::put(
            'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full/' . $code,
            [
                'Authorization' => 'Bearer ' . $token->access_token,
                'Content-Type'  => 'application/atom+xml',
                'GData-Version' => '3.0',
            ], $xml->saveXML()
        );
        if ($result->status_code != 200) {
            $message = 'The status code returned was "' . $result->status_code . '".';
            if (!(strpos($result->raw, 'Token invalid') === false)) {
                $message .= ' The token was invalid. Please disconnect and retry.';
            }
            return $message;
        } else {
            return true;
        }
    }

    public function delete(\GContacts\Feed\Entry $contact, $code)
    {
        $token = \Session::get('access_token');
        $result = \Requests::delete(
            'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full/' . $code,
            [
                'Authorization' => 'Bearer ' . $token->access_token,
                'Content-Type'  => 'application/atom+xml',
                'GData-Version' => '3.0',
                'If-Match'      => $contact->getEtag()
            ]
        );
        if ($result->status_code != 200) {
            $message = 'The status code returned was "' . $result->status_code . '".';
            if (!(strpos($result->raw, 'Token invalid') === false)) {
                $message .= ' The token was invalid. Please disconnect and retry.';
            }
            return $message;
        } else {
            return true;
        }
    }

    public function create(\DomDocument $xml)
    {
        $token = \Session::get('access_token');
        $result = \Requests::post(
            'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full',
            [
                'Authorization' => 'Bearer ' . $token->access_token,
                'Content-Type'  => 'application/atom+xml',
                'GData-Version' => '3.0',
            ], $xml->saveXML()
        );
        if ($result->status_code != 200 && $result->status_code != 201) {
            $message = 'The status code returned was "' . $result->status_code . '".';
            if (!(strpos($result->raw, 'Token invalid') === false)) {
                $message .= ' The token was invalid. Please disconnect and retry.';
            }
            return $message;
        } else {
            return true;
        }
    }

    public function getPhoto($code)
    {
        $token = \Session::get('access_token');
        $entry = $this->getContact($code);
        /** @var $link \GContacts\AtomType\Link */
        foreach ($entry->getLink() as $link) {
            if ($link->getRel() == 'http://schemas.google.com/contacts/2008/rel#photo') {
                // get photo from Google:
                $result = \Requests::get(
                    $link->getHref(), [
                        'Authorization' => 'Bearer ' . $token->access_token,
                        'Content-Type'  => 'application/x-www-form-urlencoded',
                        'GData-Version' => '3.0',
                    ]
                );
                if ($result->status_code == 200) {
                    return $result->body;
                } else {
                    return null;
                }
            }
        }
        return null;
    }

    public function getToken($tempToken)
    {
        die('Not valid in this context.');
    }

    public function logout()
    {
        $token = \Session::get('access_token');
        $URL = 'https://accounts.google.com/o/oauth2/revoke?token=' . $token->access_token;
        $result = \Requests::get($URL);
        return true;
    }
}