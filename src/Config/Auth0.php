<?php

namespace Michalsn\CodeIgniterAuth0\Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\I18n\Time;

class Auth0 extends BaseConfig
{
    public ?string $domain       = null;
    public ?string $customDomain = null;
    public ?string $clientId     = null;
    public ?string $clientSecret = null;
    public ?string $cookieSecret = null;
    public bool $cookieSecure    = true;
    public ?string $redirectUri  = null;
    public array $scope          = ['openid', 'profile', 'email'];

    /**
     * User profile defaults
     */
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

    public function formatUserProfile(array $profile, bool $update = false): array
    {
        $data = [
            'identity'      => $profile['sub'],
            'username'      => $profile['nickname'] ?? $profile['name'],
            'email'         => $profile['email'],
            'picture'       => $profile['picture'],
            'language'      => $profile['locale'] ?? $this->defaultLanguage,
            'timezone'      => $this->defaultTimezone,
            'last_login_at' => Time::now('UTC')->format('Y-m-d H:i:s'),
        ];

        if ($update) {
            unset(
                $data['username'], $data['language'], $data['timezone']
            );
        }

        return $data;
    }
}
