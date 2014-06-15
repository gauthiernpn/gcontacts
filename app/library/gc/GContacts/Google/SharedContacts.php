<?php
namespace GContacts\Google;

class SharedContacts implements SharedContactsInterface
{

    public function all()
    {
        $URL = 'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full';
        $result = \Requests::get(
            $URL, [
                'Authorization' => 'AuthSub token="' . \Session::get('token') . '"',
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'GData-Version' => '3.0',
            ]
        );
        $return = [];
        if ($result->status_code == 200) {
            $rawBody = $result->body;
            $feed = \Zend\Feed\Reader\Reader::importString($rawBody);

            if ($feed->count() > 0) {
                foreach ($feed as $entry) {
                    $current = \GContacts\Feed\EntryParser::parseFromXML($entry->saveXml());
                    $return[] = $current;
                }
            }
        } else {
            $return = 'Getting contacts returned an error code: ' . $result->status_code;
        }
        return $return;
    }

    public function getContact($code)
    {
        $URL = 'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full/' . $code;
        $result = \Requests::get(
            $URL, [
                'Authorization' => 'AuthSub token="' . \Session::get('token') . '"',
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
        $mime = $photo->getClientMimeType();
        $result = \Requests::put(
            $URL, [
                'Authorization' => 'AuthSub token="' . \Session::get('token') . '"',
                'Content-Type'  => $mime,
                'GData-Version' => '3.0',
                'If-Match'      => '*'
            ], file_get_contents($photo->getRealPath())
        );
    }

    public function update(\DomDocument $xml, $code)
    {
        $result = \Requests::put(
            'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full/' . $code,
            [
                'Authorization' => 'AuthSub token="' . \Session::get('token') . '"',
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
        $result = \Requests::delete(
            'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full/' . $code,
            [
                'Authorization' => 'AuthSub token="' . \Session::get('token') . '"',
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
        $result = \Requests::post(
            'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full',
            [
                'Authorization' => 'AuthSub token="' . \Session::get('token') . '"',
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

    public function getPhoto($code)
    {
        $entry = $this->getContact($code);
        /** @var $link \GContacts\AtomType\Link */
        foreach ($entry->getLink() as $link) {
            if ($link->getRel() == 'http://schemas.google.com/contacts/2008/rel#photo') {
                // get photo from Google:
                $result = \Requests::get(
                    $link->getHref(), [
                        'Authorization' => 'AuthSub token="' . \Session::get('token') . '"',
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
        $URL = 'https://www.google.com/accounts/AuthSubSessionToken';
        $result = \Requests::get(
            $URL,
            [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'AuthSub token="' . $tempToken . '"',
                'User-Agent'    => 'GContacts/0.1',
            ]
        );
        $token = str_replace('Token=', '', $result->body);
        return trim($token);
    }

    public function logout()
    {
        $URL = 'https://www.google.com/accounts/AuthSubRevokeToken';
        $result = \Requests::get(
            $URL,
            [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'AuthSub token="' . \Session::get('token') . '"',
                'User-Agent'    => 'GContacts/0.1',
            ]
        );
        return true;
    }
}