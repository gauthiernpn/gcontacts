<?php


class PhotoController extends BaseController
{
    public function __construct(GContacts\Google\SharedContactsInterface $contacts)
    {
        $this->contacts = $contacts;
    }

    public function photo($code)
    {
        if($code == 'null') {
            $entry = 'Photo not found';
        } else {
            $entry = $this->contacts->getPhoto($code);
        }

        if ($entry == 'Photo not found' || is_null($entry)) {
            // return dummy image.
            $image = app_path('../public') . '/assets/i/default_profile.jpg';
            header("Content-Type: image/jpeg");
            header("Content-Length: " . (string)(filesize($image)));
            echo file_get_contents($image);
            exit();
        } else {
            header("Content-Type: image/jpeg");
            echo $entry;
            exit();
        }

    }

} 