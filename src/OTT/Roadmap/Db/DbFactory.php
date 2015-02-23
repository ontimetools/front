<?php

namespace OTT\Roadmap\Db;

use OTT\Roadmap\Exception\InvalidDatabaseAdapterException;

/**
 * Class DbFactory
 * @package OTT\Roadmap\Db
 */
class DbFactory
{
    /**
     * @param $adapter
     * @param $connection
     * @return object
     * @throws InvalidDatabaseAdapterException
     */
    public static function get($adapter, $connection)
    {
        $classAdapter = sprintf('\\OTT\\Roadmap\\Db\\Adapter\\%s', ucfirst($adapter));
        if (!class_exists($classAdapter)) {
            throw new InvalidDatabaseAdapterException('Database adapter not found.');
        }

        return (new \ReflectionClass($classAdapter))->newInstance($connection);
    }
}
