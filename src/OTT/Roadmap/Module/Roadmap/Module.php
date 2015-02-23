<?php

namespace OTT\Roadmap\Module\Roadmap;

use Igorw\Silex\ConfigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use OTT\Roadmap\Module\Module as AbstractModule;
use OTT\Roadmap\Server;

class Module extends AbstractModule
{
    /**
     *  Routes registration from Yaml configuration file (with version management)
     */
    protected function initModuleRouter()
    {
        Server::getInstance()->register(
            new ConfigServiceProvider(sprintf('%s/config/routes.yml', __DIR__, $this->getModuleName()))
        );
        $conf = Server::getService(sprintf('conf.routes.%s', strtolower($this->getModuleName())));
        if (is_array($conf)) {
            foreach ($conf as $name => $route) {
                Server::getInstance()->match(
                    sprintf('/%s', $route['pattern']),
                    sprintf('OTT\\Roadmap\\Module\\%s\\Controller\\%s', $this->getModuleName(), $route['defaults']['_controller'])
                )->bind($name)->method(isset($route['method']) ? $route['method'] : 'GET');
            }
            Server::getInstance()->register(new UrlGeneratorServiceProvider());
        }
    }
}
