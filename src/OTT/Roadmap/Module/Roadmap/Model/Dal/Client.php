<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Dal;

use OTT\Roadmap\Module\Roadmap\Model\Entity\Client as ClientEntity;
use OTT\Roadmap\Module\Roadmap\Model\Exception\DatabaseException;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Client as ClientFilter;

/**
 * Class Client
 * @package OTT\Roadmap\Module\Roadmap\Model\Dal
 */
class Client extends DalAbstract
{
    /**
     * @param ClientFilter $filter
     * @return array
     */
    public static function getClient(ClientFilter $filter)
    {
        $query = self::getQb()
            ->select('*')
            ->from(self::DBTABLE_CLIENT);
        if (null !== $filter->getId()) {
            $query->andWhere('id = :id')
                ->setParameter('id', $filter->getId());
        }
        if (null !== $filter->getSubdomain()) {
            $query->andWhere('subdomain = :subdomain')
                ->setParameter('subdomain', $filter->getSubdomain());
        }
        if (null !== $filter->isActive()) {
            $suffix = $filter->isActive() ? 'NULL' : 'NOT NULL';
            $query->andWhere('date_delete IS ' . $suffix);
        }
        /** @var \PDOStatement $result */
        $result = $query->execute();

        return $result->fetch();
    }

    /**
     * @param ClientFilter $filter
     * @return bool
     */
    public static function deleteClient(ClientFilter $filter)
    {
        $entity = new ClientEntity();
        $entity->setDateDelete(new \DateTime());
        $result = self::updateClient($entity, $filter);

        return $result;
    }

    /**
     * @param ClientEntity $entity
     * @param ClientFilter $filter
     * @return bool
     * @throws \Exception
     */
    public static function updateClient(ClientEntity $entity, ClientFilter $filter)
    {
        $result = false;
        $query = self::getQb();
        $dbCon = $query->getConnection();
        if (null !== $entity->getDateDelete()) {
            $query->set('date_delete', ':date_delete')
                ->setParameter('date_delete', self::formatDateToDb($entity->getDateDelete()));
        } else {
            if (null !== $entity->getHourPerDay()) {
                $query->set('setting_hour_per_day', ':hour_per_day')
                    ->setParameter('hour_per_day', $entity->getHourPerDay());
            }
            if (null !== $entity->getDayPerWeek()) {
                $query->set('setting_day_per_week', ':day_per_week')
                    ->setParameter('day_per_week', $entity->getDayPerWeek());
            }
            if (null !== $entity->getWeekPerIteration()) {
                $query->set('setting_week_per_iteration', ':week_per_iteration')
                    ->setParameter('week_per_iteration', $entity->getWeekPerIteration());
            }
            $query->set('date_update', ':date_update')
                ->setParameter('date_update', self::formatDateToDb(true));
        }
        if (null !== $filter->getId()) {
            $query->andWhere('id = :id')
                ->setParameter('id', $filter->getId());
        }

        $dbCon->transactional(function () use ($entity, $filter, &$query, &$result) {
            $retUpdate = $query->update(self::DBTABLE_CLIENT, 'c')->execute();
            if (false === $retUpdate) {
                throw new DatabaseException('SQL issue: update client.');
            }
            $result = true;
        });

        return $result;
    }
} 