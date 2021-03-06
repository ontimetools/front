<?php

namespace OTT\Roadmap;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Igorw\Silex\ConfigServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use OTT\Roadmap\Db\DbFactory;
use OTT\Roadmap\Exception\ControllerException;
use OTT\Roadmap\Exception\ErrorSQLStatementException;
use OTT\Roadmap\Exception\RequiredExtensionException;
use OTT\Roadmap\Module\IModule;
use OTT\Roadmap\Module\Module as ModuleAbstract;
use OTT\Roadmap\Helper\Configuration as ConfHelper;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Application
 * @package OTT\Roadmap
 */
class Application
{
    /**
     *
     */
    public function __construct()
    {
        $this->checkIntegrity();
        $this->registerGlobalConfiguration();
    }

    /**
     *
     */
    public function run()
    {
        try {
            $this->registerServiceLogger();
            $this->registerServiceSession();
            $this->registerAbstractLayoutDatabase();
            $this->registerServiceTmplEngine();
            $this->registerServiceStorage();

            $this->loadModules();

            Server::getInstance()->run();
        } catch (ErrorSQLStatementException $e) {
            $log = Server::getService('logger.sql');
            $log->addError($e->getMessage());
            $log->addError($e->getTraceAsString());
        } catch (ControllerException $e) {
            $log = Server::getService('logger.controller');
            $log->addError($e->getMessage());
            $log->addError($e->getTraceAsString());
        } catch (\Exception $e) {
            $log = Server::getService('monolog');
            $log->addError($e->getMessage());
            $log->addError($e->getTraceAsString());
        }
    }

    /**
     * We'll check application integrity (extensions loaded etc.)
     */
    private function checkIntegrity()
    {
        // We need to check if pdo_mysql is enabled (no adapter for now so we have to do this)
        if (!extension_loaded('pdo_mysql')) {
            throw new RequiredExtensionException('Install `pdo_mysql` extension before.');
        }
        // We're using the twig i18n and need intl
        if (!extension_loaded('intl')) {
            throw new RequiredExtensionException('Install `intl` extension before.');
        }
    }

    /**
     * @throws Exception\InvalidConfFileException
     */
    private function registerGlobalConfiguration()
    {
        Server::getInstance()->register(new ConfigServiceProvider(ConfHelper::getMainFile('config.yml')));
        $conf = Server::getService('conf.app');
        // Silex debugging
        Server::setService(
            'debug',
            isset($conf['silex']['debug']) ? $conf['silex']['debug'] : false
        );
        // PHP Settings
        if (isset($conf['php_settings']) && is_array($conf['php_settings'])) {
            foreach ($conf['php_settings'] as $key => $value) {
                if (false === ini_set($key, $value)) {
                    throw new InvalidConfigurationException(
                        sprintf('Conf `%s` => `%s` does not exist.', $key, $value)
                    );
                }
            }
        }
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    private function registerAbstractLayoutDatabase()
    {
        $conf = Server::getService('conf.app');

        if (!isset($conf['database'])) {
            throw new InvalidConfigurationException('Configuration for `database` does not exist');
        }
        Server::setService('db', DriverManager::getConnection($conf['database'], new Configuration()));
    }

    /**
     *
     */
    private function registerServiceTmplEngine()
    {
        $app = Server::getInstance();
        // We register the default engine
        $app->register(new TwigServiceProvider());
        // We define twig globals, filters, extensions etc. (if needed)
        Server::setService('twig', $app->share($app->extend('twig', function ($twig) {
            $app = Server::getInstance();
            /** @var $twig \Twig_Environment */
            $twig->addExtension(new \Twig_Extensions_Extension_I18n());
            $twig->addExtension(new \Twig_Extensions_Extension_Intl());
            if (isset($app['url_generator'])) {
                $twig->addExtension(new RoutingExtension($app['url_generator']));
            }

            $twig->getExtension('core')->setNumberFormat(0, ',', ' ');
            return $twig;
        })));
    }

    /**
     *  Register logger service todo: let "main" logger and add "module" logger in module register logger method
     */
    private function registerServiceLogger()
    {
        $app = Server::getInstance();
        $conf = Server::getService('conf.app');
        $logPattern = LOG_PATH . '/%s';
        if (!isset($conf['monolog'])) {
            throw new InvalidConfigurationException('Configuration for `monolog` does not exist');
        }
        $confMonolog = $conf['monolog'];
        if (!isset($confMonolog['handlers'])) {
            throw new InvalidConfigurationException('Configuration for `handlers` does not exist');
        }
        if (!isset($confMonolog['app']) || !isset($confMonolog['app'])) {
            throw new InvalidConfigurationException('Configuration for `monolog.app` does not exist');
        }
        // We set default logger for the application
        $app->register(
            new MonologServiceProvider(),
            ['monolog.logfile' => sprintf($logPattern, $confMonolog['app'])]
        );
        // We set other loggers from configuration handlers file
        foreach ($confMonolog['handlers'] as $handler => $path) {
            Server::setService('logger.' . $handler, $app->share(function ($app) use ($handler) {
                return new Logger($handler);
            }));
            Server::getService('logger.' . $handler)->pushHandler(
                new StreamHandler(sprintf($logPattern, $path, Logger::WARNING))
            );
        }
    }

    /**
     * Register service storage (for all modules todo: mb later => one per module)
     */
    private function registerServiceStorage()
    {
        $conf = Server::getService('conf.app');
        if (!isset($conf['database'])) {
            throw new InvalidConfigurationException('Configuration for `monolog` does not exist');
        }
        $db = $conf['database'];
        Server::setService('storage', DbFactory::get($db['adapter'], $db));
    }

    /**
     * Session registration (+ define lifetime todo: put this in constant (yaml?))
     */
    private function registerServiceSession()
    {
        Server::getInstance()->register(
            new SessionServiceProvider(),
            ['session.storage.options' => ['cookie_lifetime' => 1296000]]
        );
    }

    /**
     * Load modules from configuration file
     */
    protected function loadModules()
    {
        $conf = Server::getService('conf.app');
        if (!isset($conf['silex']['modules'])) {
            throw new InvalidConfigurationException('Configuration for `modules` does not exist');
        }
        $confModules = $conf['silex']['modules'];
        if (!is_array($conf['silex']['modules'])) {
            throw new InvalidConfigurationException('Configuration for `modules` is not an array');
        }
        foreach ($confModules as $idModule => $moduleOptions) {
            $nsModuleName =
                sprintf('\\OTT\\Roadmap\\Module\\%s\\Module', ucfirst(strtolower($moduleOptions['name'])));
            /** @var IModule $module */
            $module = (new \ReflectionClass($nsModuleName))->newInstance();
            if ($module instanceof ModuleAbstract) {
                $module->setModuleName($moduleOptions['name']);
                $module->setModuleBaseUrl($moduleOptions['url']);
                $module->init();
            }
        }
    }
}
