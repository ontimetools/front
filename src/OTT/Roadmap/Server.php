<?php

namespace OTT\Roadmap;

use Silex\Application as SilexApplication;

/**
 * Class Server
 * @package OTT\Roadmap
 */
class Server
{
    /** @var  Application */
    private static $instance = null;

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new SilexApplication();
        }

        return self::$instance;
    }

    /**
     * @param $serviceName
     * @param bool $throw
     * @return mixed
     */
    public static function getService($serviceName, $throw = true)
    {
        $return = null;
        if (!isset(self::getInstance()[$serviceName])) {
            if ($throw) {
                throw new \InvalidArgumentException(sprintf('Service `%s` does not exist.', $serviceName));
            }
        } else {
            $return = self::getInstance()[$serviceName];
        }

        return $return;
    }

    /**
     * @param $serviceName
     * @param $element
     */
    public static function setService($serviceName, $element)
    {
        self::getInstance()[$serviceName] = $element;
    }


    /**
     * @param $serviceName
     */
    public static function unsetService($serviceName)
    {
        if (!isset(self::getInstance()[$serviceName])) {
            throw new \InvalidArgumentException(sprintf('Service `%s` does not exist.', $serviceName));
        }

        unset(self::getInstance()[$serviceName]);
    }

    /**
     * @param $serviceName
     * @return bool
     */
    public static function issetService($serviceName)
    {
        return isset(self::getInstance()[$serviceName]);
    }
}
