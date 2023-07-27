<?php

function authenticated(): bool
{
    return service('auth0')->isAuthenticated();
}

function user_id()
{
    return user('id');
}

function user(?string $key = null)
{
    static $user = null;

    if ($user === null) {
        $session = service('auth0')->getCredentials();
        if ($session !== null) {
            $user = model('UserModel')->findByIdentity($session->user['sub']);
        }
    }

    return $key === null ? $user : $user[$key] ?? null;
}
