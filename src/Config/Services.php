<?php

namespace Michalsn\CodeIgniterAuth0\Config;

use CodeIgniter\Config\BaseService;
use Michalsn\CodeIgniterAuth0\Auth0;
use Michalsn\CodeIgniterAuth0\Config\Auth0 as Auth0Config;

class Services extends BaseService
{
    /**
     * Return the auth0 class.
     */
    public static function auth0(?Auth0Config $config = null, bool $getShared = true): Auth0
    {
        if ($getShared) {
            return static::getSharedInstance('auth0', $config);
        }

        $config ??= config(Auth0Config::class);

        return new Auth0($config);
    }
}
