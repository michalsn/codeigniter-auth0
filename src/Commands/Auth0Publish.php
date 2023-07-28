<?php

namespace Michalsn\CodeIgniterKinde\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Publisher\Publisher;
use Throwable;

/**
 * @codeCoverageIgnore
 */
class Auth0Publish extends BaseCommand
{
    protected string $group       = 'Auth0';
    protected string $name        = 'Auth0:publish';
    protected string $description = 'Publish Kinde config file into the current application.';

    /**
     * @return void
     */
    public function run(array $params)
    {
        $source = service('autoloader')->getNamespace('Michalsn\\CodeIgniterAuth0')[0];

        $publisher = new Publisher($source, APPPATH);

        try {
            $publisher->addPaths([
                'Config/Auth0.php',
            ])->merge(false);
        } catch (Throwable $e) {
            $this->showError($e);

            return;
        }

        foreach ($publisher->getPublished() as $file) {
            $contents = file_get_contents($file);
            $contents = str_replace('namespace Michalsn\\CodeIgniterAuth0\\Config', 'namespace Config', $contents);
            $contents = str_replace('use CodeIgniter\\Config\\BaseConfig', 'use Michalsn\\CodeIgniterAuth0\\Config\\Auth0 as BaseAuth0', $contents);
            $contents = str_replace('class Auth0 extends BaseConfig', 'class Auth0 extends BaseAuth0', $contents);
            file_put_contents($file, $contents);
        }

        CLI::write(CLI::color('  Published! ', 'green') . 'You can customize the configuration by editing the "app/Config/Auth0.php" file.');
    }
}
