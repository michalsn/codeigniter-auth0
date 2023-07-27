<?php

namespace Michalsn\CodeIgniterAuth0\Config;

use Config\Services;

$routes = Services::routes();

$routes->get('login', static fn () => service('auth0')->login(), ['as' => 'login']);

$routes->get('logout', static fn () => service('auth0')->logout(), ['as' => 'logout', 'filter' => 'auth0Stateful']);

$routes->get('callback', static fn () => service('auth0')->callback(), ['as' => 'callback']);
