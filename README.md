# CodeIgniter Auth0

Basic integration for [Auth0](https://auth0.com/) authentication.

[![PHPStan](https://github.com/michalsn/codeigniter-auth0/actions/workflows/phpstan.yml/badge.svg)](https://github.com/michalsn/codeigniter-auth0/actions/workflows/phpstan.yml)
[![PHPCPD](https://github.com/michalsn/codeigniter-auth0/actions/workflows/phpcpd.yml/badge.svg)](https://github.com/michalsn/codeigniter-auth0/actions/workflows/phpcpd.yml)
[![Psalm](https://github.com/michalsn/codeigniter-auth0/actions/workflows/psalm.yml/badge.svg)](https://github.com/michalsn/codeigniter-auth0/actions/workflows/psalm.yml)
[![Rector](https://github.com/michalsn/codeigniter-auth0/actions/workflows/rector.yml/badge.svg)](https://github.com/michalsn/codeigniter-auth0/actions/workflows/rector.yml)
[![Deptrac](https://github.com/michalsn/codeigniter-auth0/actions/workflows/deptrac.yml/badge.svg)](https://github.com/michalsn/codeigniter-auth0/actions/workflows/deptrac.yml)

![PHP](https://img.shields.io/badge/PHP-%5E8.0-blue)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-%5E4.3-blue)

### Installation

#### Composer

    composer require michalsn/codeigniter-auth0

    composer require guzzlehttp/guzzle guzzlehttp/psr7 http-interop/http-factory-guzzle

#### Manually

In the example below we will assume, that files from this project will be located in `app/ThirdParty/auth0` directory.

Download this project and then enable it by editing the `app/Config/Autoload.php` file and adding the `Michalsn\CodeIgniterAuth0` namespace to the `$psr4` array, like in the below example:

```php
<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{
    // ...
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For custom app namespace
        'Config'      => APPPATH . 'Config',
        'Michalsn\CodeIgniterAuth0' => APPPATH . 'ThirdParty/auth0/src',
    ];

    // ...
```
Also add the required helper to the same file under `$files` array:

```php
    // ...
    public $files = [
        APPPATH . 'ThirdParty/auth0/src/Common.php',
    ];

    // ...
```

You will still need to install some dependencies:

    composer require guzzlehttp/guzzle guzzlehttp/psr7 http-interop/http-factory-guzzle

### Database

    php spark migrate --all

### Config

See what configuration variables can be set by looking at the `src/Config/Auth0.php` file and use the `.env` file to set them.

See the [getting started](https://auth0.com/docs/libraries/auth0-php) article for reference.

### Routes

- `login`
- `logout`
- `callback`

### Filters

- `auth0Stateful`

### Commands

To copy config file to the application namespace.

    php spark auth0:publish

### Helper functions

- `authenticated()` will check if current user is authenticated
- `user_id()` will return current user ID (database)
- `user()` will return current user info (database)
- `auth0_user()` will return the Auth0 user array or `null`

### Example

```php
<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (! service('auth0')->isAuthenticated()) {
            return $this->response->setHeader(401)->setBody('401 Unauthorized');
        }

        return view('home/index', $data);
    }
}
```
