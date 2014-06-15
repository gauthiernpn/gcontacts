<?php
namespace GContacts\Google;

use Illuminate\Support\ServiceProvider;

class SharedContactsServiceProvider extends ServiceProvider
{
    // Triggered automatically by Laravel
    public function register()
    {
        if($this->app->environment() == 'app-engine') {
            $this->app->bind(
                'GContacts\Google\SharedContactsInterface', // interface
                'GContacts\Google\SharedContactsGAE' // class
            );
        } else {
            $this->app->bind(
                'GContacts\Google\SharedContactsInterface', // interface
                'GContacts\Google\SharedContactsOAuth2' // class
            );
        }

    }

}