<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Dal;

use OTT\Roadmap\Server;
use Doctrine\DBAL\Connection;

/**
 * Class DalAbstract
 * @package OTT\Roadmap\Module\Roadmap\Model\Dal
 */
abstract class DalAbstract
{
    const DBTABLE_CLIENT = 'client';
    const DBTABLE_DOCUMENT = 'document';
    const DBTABLE_DOCUMENT_ENTRY = 'document_entry';
    const DBTABLE_SETTING = 'setting';

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public static function getQb()
    {
        /** @var Connection $db */
        $db = Server::getService('db');
        return $db->createQueryBuilder();
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public static function getDb()
    {
        return Server::getService('db');
    }

    /**
     * @param null $date
     * @return \DateTime|bool
     */
    public static function formatDateToDb($date)
    {
        $result = null;
        if (is_bool($date) && true === $date) {
            $date = new \DateTime();
        }
        if ($date instanceof \DateTime) {
            $result = $date->format('Y-m-d H:i:s');
        }
        return $result;
    }
}