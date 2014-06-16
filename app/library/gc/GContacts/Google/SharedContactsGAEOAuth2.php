<?php
namespace GContacts\Google;

class SharedContactsGAEOAuth2 implements SharedContactsInterface
{

    public function all()
    {
        $URL = 'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full';
        $token = \Session::get('access_token');

        $headers
            = "Authorization: AuthSub token=\"{$token->access_token}\"\r\nContent-Type: application/x-www-form-urlencoded\r\nGData-Version: 3.0\r\n";
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => $headers
            ]
        ];

        $context = stream_context_create($opts);
        $result = @file_get_contents($URL, false, $context);

        $isError = strpos($result, 'Error 403 (Forbidden)');

        if ($result === false || !($isError === false)) {
            return 'There was an error while retrieving contacts. Do you have access to the shared contacts of '
            . \Session::get('hd') . '?';
        }
        $return = [];
        try {
            $feed = \Zend\Feed\Reader\Reader::importString($result);

            if ($feed->count() > 0) {
                foreach ($feed as $entry) {
                    $current = \GContacts\Feed\EntryParser::parseFromXML($entry->saveXml());
                    $return[] = $current;
                }
            }
        } catch (\Zend\Feed\Reader\Exception\RuntimeException $e) {
            return 'There was an error while retrieving contacts. Do you have access to the shared contacts of '
            . \Session::get('hd') . '?';
        }
        return $return;
    }

    public function getContact($code)
    {
        $URL = 'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full/' . $code;
        $token = \Session::get('access_token');
        $headers
            = "Authorization: AuthSub token=\"{$token->access_token}\"\r\nContent-Type: application/x-www-form-urlencoded\r\nGData-Version: 3.0\r\n";
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => $headers
            ]
        ];
        $context = stream_context_create($opts);
        $result = @file_get_contents($URL, false, $context);
        if ($result === false) {
            return 'Error while retreiving contact.';
        }
        return \GContacts\Feed\EntryParser::parseFromXML($result);
    }

    public function buildEntryFromArray($code, $arr)
    {
        return \GContacts\Feed\Entry::buildEntryFromArray($code, $arr);
    }

    public function uploadPhoto($code, \Symfony\Component\HttpFoundation\File\UploadedFile $photo)
    {
        die('Not implemented.');
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
        $token = \Session::get('access_token');
        $URL = 'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full/' . $code;
        $headers
            = "Authorization: AuthSub token=\"{$token->access_token}\"\r\nContent-Type: application/atom+xml\r\nGData-Version: 3.0\r\n";
        $opts = [
            'http' => [
                'method'  => 'PUT',
                'header'  => $headers,
                'content' => $xml->saveXML()
            ]
        ];
        $context = stream_context_create($opts);
        $result = @file_get_contents($URL, false, $context);
        if ($result === false) {
            return 'An error occurred while updating this contact.';
        }
        return true;
    }

    public function delete(\GContacts\Feed\Entry $contact, $code)
    {
        $token = \Session::get('access_token');
        $URL = 'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full/' . $code;
        $headers = "Authorization: AuthSub token=\"{$token->access_token}\"\r\nContent-Type: application/atom+xml\r\nGData-Version: 3.0\r\nIf-Match: "
            . $contact->getEtag() . "\r\n";

        $opts = [
            'http' => [
                'method' => 'DELETE',
                'header' => $headers
            ]
        ];
        $context = stream_context_create($opts);
        $result = @file_get_contents($URL, false, $context);
        if ($result === false) {
            return 'Error while retreiving contact.';
        }
        return true;
    }

    public function create(\DomDocument $xml)
    {
        $URL = 'https://www.google.com/m8/feeds/contacts/' . \Session::get('hd') . '/full';
        $token = \Session::get('access_token');

        $headers
            = "Authorization: AuthSub token=\"{$token->access_token}\"\r\nContent-Type: application/atom+xml\r\nGData-Version: 3.0\r\n";
        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => $headers,
                'content' => $xml->saveXML()
            ]
        ];

        $context = stream_context_create($opts);
        $result = @file_get_contents($URL, false, $context);
        if ($result === false) {
            return 'Error while retrieving contact.';
        }
        return true;
    }

    public function getPhoto($code)
    {
        $entry = $this->getContact($code);
        /** @var $link \GContacts\AtomType\Link */
        foreach ($entry->getLink() as $link) {
            if ($link->getRel() == 'http://schemas.google.com/contacts/2008/rel#photo') {
                // get photo from Google:
                $URL = $link->getHref();
                $token = \Session::get('access_token');
                $headers
                    = "Authorization: AuthSub token=\"{$token->access_token}\"\r\nContent-Type: application/atom+xml\r\nGData-Version: 3.0\r\n";
                $opts = [
                    'http' => [
                        'method' => 'GET',
                        'header' => $headers,
                    ]
                ];
                $context = stream_context_create($opts);
                $result = @file_get_contents($URL, false, $context);
                if ($result === false) {
                    return null;
                }
                return $result;
            }
        }
        return null;
    }

    public function getToken($tempToken)
    {
        $URL = 'https://www.google.com/accounts/AuthSubSessionToken';

        $context = [
            'http' => [
                'method'  => 'GET',
                'header'  =>
                    'Authorization: AuthSub token="' . $tempToken . '"' . "\r\n" .
                    'Content-Type: application/x-www-form-urlencoded' . "\r\n" .
                    'User-Agent: GContacts/0.1' . "\r\n",
                'content' => null
            ]
        ];
        $context = stream_context_create($context);
        $result = @file_get_contents($URL, null, $context);
        if ($result === false) {
            return 'Error while getting token.';
        }
        $token = str_replace('Token=', '', $result);
        return $token;
    }

    public function logout()
    {
        $URL = 'https://www.google.com/accounts/AuthSubRevokeToken';
        $token = \Session::get('access_token');
        $context = [
            'http' => [
                'method'  => 'GET',
                'header'  =>
                    'Authorization: AuthSub token="' . $token->access_token . '"' . "\r\n" .
                    'Content-Type: application/x-www-form-urlencoded' . "\r\n" .
                    'User-Agent: GContacts/0.1' . "\r\n",
                'content' => null
            ]
        ];
        $context = stream_context_create($context);
        $result = @file_get_contents($URL, null, $context);
        if ($result === false) {
            return 'Error while getting token.';
        }
        return true;
    }
}