<?php

namespace OTT\Roadmap\Module\Roadmap\Model\Service;

use OTT\Roadmap\Module\Roadmap\Model\Service\Message\ServiceHeader;
use OTT\Roadmap\Server;
use Silex\Provider\SessionServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ServiceAbstract
 * @package OTT\Roadmap\Module\Roadmap\Model\Service
 */
abstract class ServiceAbstract
{
    /** @var array */
    private static $instances = [];
    /** @var Session */
    private $session;

    /**
     *
     */
    public function __construct()
    {
        $this->session = Server::getService('session');
    }

    /**
     * @return static
     */
    final public static function getInstance()
    {
        $instanceName = get_called_class();
        if (!isset(self::$instances[$instanceName])) {
            self::$instances[$instanceName] = new $instanceName();
        }

        return self::$instances[$instanceName];
    }


    /**
     *
     */
    protected function getHeaders()
    {
        $headers = new ServiceHeader();
        $headers->setUser(Application::getInstance()->getCurrentUser()->getResult());
        $headers->setClient(Application::getInstance()->getCurrentClient()->getResult());

        return $headers;
    }

    /**
     * @return mixed|Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }
} 