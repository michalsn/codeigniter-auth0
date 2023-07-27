<?php

namespace Michalsn\CodeIgniterAuth0\Config;

use Michalsn\CodeIgniterAuth0\Filters\Auth0Stateful;

class Registrar
{
    /**
     * Register the CodeIgniterAuth0Stateful filter.
     */
    public static function Filters(): array
    {
        return [
            'aliases' => [
                'auth0Stateful' => Auth0Stateful::class,
            ],
        ];
    }
}
