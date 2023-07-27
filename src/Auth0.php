<?php

namespace Michalsn\CodeIgniterAuth0;

use Auth0\SDK\Auth0 as Auth0SDK;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Store\SessionStore;
use BadMethodCallException;
use CodeIgniter\I18n\Time;
use Michalsn\CodeIgniterAuth0\Config\Auth0 as Auth0Config;
use Michalsn\CodeIgniterAuth0\Models\UserModel;

class Auth0
{
    private Auth0SDK $auth0;

    public function __construct(private Auth0Config $config)
    {
        $configuration = new SdkConfiguration(
            domain: $config->domain,
            customDomain: $config->customDomain,
            clientId: $config->clientId,
            redirectUri: $config->redirectUri,
            clientSecret: $config->clientSecret,
            scope: $config->scope,
            cookieSecret: $config->cookieSecret,
            cookieSecure: $config->cookieSecure,
        );

        $sessionStore = new SessionStore($configuration);
        $configuration->setSessionStorage($sessionStore);

        $this->auth0 = new Auth0SDK($configuration);
    }

    public function callback()
    {
        $session = $this->auth0->getCredentials();

        // Is this end-user already signed in?
        if ($session === null && $this->auth0->getExchangeParameters() !== null) {
            $this->auth0->exchange();

            // Authentication complete!
            $profile = $this->auth0->getUser();

            $userModel = model(UserModel::class);

            if ($userModel->findByIdentity($profile['sub'])) {
                $user = $this->formatUserProfile($profile, true);
                $userModel->updateByIdentity($profile['sub'], $user);
            } else {
                $user = $this->formatUserProfile($profile);
                $userModel->insert($user);
            }

            return $this->config->afterCallbackSuccess();
        }

        return $this->config->afterCallbackError();
    }

    public function login()
    {
        $this->auth0->clear();
        $url = $this->auth0->login($this->config->loginCallbackUri());

        return redirect()->to($url);
    }

    public function logout()
    {
        $url = $this->auth0->logout($this->config->logoutCallbackUri());

        return redirect()->to($url);
    }

    protected function formatUserProfile(array $profile, bool $update = false): array
    {
        $data = [
            'identity'      => $profile['sub'],
            'username'      => $profile['nickname'] ?? $profile['name'],
            'email'         => $profile['email'],
            'picture'       => $profile['picture'],
            'language'      => $profile['locale'] ?? $this->config->defaultLanguage,
            'timezone'      => $this->config->defaultTimezone,
            'last_login_at' => Time::now('UTC')->format('Y-m-d H:i:s'),
        ];

        if ($update) {
            unset(
                $data['username'], $data['language'], $data['timezone']
            );
        }

        return $data;
    }

    public function __call(string $name, array $args)
    {
        if (method_exists($this->auth0, $name)) {
            return call_user_func_array([$this->auth0, $name], $args);
        }

        throw new BadMethodCallException("Method {$name} does not exist in Auth0 SDK.");

    }
}
