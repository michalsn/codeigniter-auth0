<?php

namespace Michalsn\CodeIgniterAuth0\Config;

use CodeIgniter\Config\BaseConfig;

class Auth0 extends BaseConfig
{
    public ?string $domain         = null;
    public ?string $customDomain   = null;
    public ?string $clientId       = null;
    public ?string $clientSecret   = null;
    public ?string $cookieSecret   = null;
    public bool $cookieSecure      = true;
    public ?string $redirectUri    = null;
    public array $scope            = ['openid', 'profile', 'email'];
    public string $defaultLanguage = 'pl';
    public string $defaultTimezone = 'Europe/Warsaw';

    public function afterCallbackSuccess()
    {
        return redirect()->to('/');
    }

    public function afterCallbackError()
    {
        return redirect()->to('/');
    }

    public function loginCallbackUri()
    {
        return url_to('callback');
    }

    public function logoutCallbackUri()
    {
        return site_url('/');
    }
}
