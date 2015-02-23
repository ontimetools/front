<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Dal;

use OTT\Roadmap\Module\Roadmap\Model\Entity\Setting as SettingEntity;
use OTT\Roadmap\Module\Roadmap\Model\Exception\DatabaseException;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Setting as SettingFilter;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class Setting
 * @package OTT\Roadmap\Module\Roadmap\Model\Dal
 */
class Setting extends DalAbstract
{
    /**
     * @param SettingFilter $filter
     * @return array
     */
    public static function getSetting(SettingFilter $filter)
    {
        $query = self::getQb();
        self::prepareParametersForSelect($query, $filter);
        /** @var \PDOStatement $result */
        $result = $query->execute();

        return $result->fetch();
    }

    /**
     * @param SettingFilter $filter
     * @return array
     */
    public static function getSettings(SettingFilter $filter)
    {
        $query = self::getQb();
        self::prepareParametersForSelect($query, $filter);
        /** @var \PDOStatement $result */
        $result = $query->execute();

        return $result->fetchAll();
    }

    /**
     * @param SettingFilter $filter
     * @return bool
     */
    public static function deleteSetting(SettingFilter $filter)
    {
        $entity = new SettingEntity();
        $entity->setDateDelete(new \DateTime());
        $result = self::updateSetting($entity, $filter);

        return $result;
    }

    /**
     * @param SettingEntity $entity
     * @return bool
     * @throws DatabaseException
     * @throws \Exception
     */
    public static function createSetting(SettingEntity $entity)
    {
        $result = null;
        $query = self::getQb();
        $dbCon = $query->getConnection();
        self::prepareParameters($query, $entity);
        $dbCon->transactional(function () use ($entity, &$query, &$result) {
            $query->insert(self::DBTABLE_SETTING, 's')->execute();
            $result = self::getDb()->lastInsertId();
            if (false === $result) {
                throw new DatabaseException('SQL issue: create setting.');
            }
        });

        return $result;
    }

    /**
     * @param SettingEntity $entity
     * @param SettingFilter $filter
     * @return bool
     * @throws \Exception
     */
    public static function updateSetting(SettingEntity $entity, SettingFilter $filter)
    {
        $result = false;
        $query = self::getQb();
        $dbCon = $query->getConnection();
        self::prepareParameters($query, $entity, $filter);
        $dbCon->transactional(function () use ($entity, $filter, &$query, &$result) {
            $retUpdate = $query->update(self::DBTABLE_SETTING, 's')->execute();
            if (false === $retUpdate) {
                throw new DatabaseException('SQL issue: update setting.');
            }
            $result = true;
        });

        return $result;
    }

    /**
     * @param QueryBuilder $query
     * @param SettingFilter $filter
     */
    private static function prepareParametersForSelect(QueryBuilder &$query, SettingFilter $filter)
    {
        $query->select('*')
            ->from(self::DBTABLE_SETTING);
        if (null !== $filter->getId()) {
            $query->andWhere('id = :id')
                ->setParameter('id', $filter->getId());
        }
        if (null !== $filter->getClientId() && is_int($filter->getParentId())) {
            $query->andWhere('client_id = :client_id')
                ->setParameter('client_id', $filter->getClientId());
        }
        if (null !== $filter->getParentId() && is_int($filter->getParentId())) {
            $query->andWhere('setting_id = :setting_id')
                ->setParameter('setting_id', $filter->getParentId());
        } elseif (null === $filter->getParentId()) {
            $query->andWhere('setting_id IS NULL');
        }
        if (null !== $filter->isActive()) {
            $suffix = $filter->isActive() ? 'NULL' : 'NOT NULL';
            $query->andWhere('date_delete IS ' . $suffix);
        }
    }

    /**
     * @param QueryBuilder $query
     * @param SettingEntity $entity
     * @param SettingFilter $filter
     * @throws DatabaseException
     */
    private static function prepareParameters(QueryBuilder &$query, SettingEntity $entity, SettingFilter $filter = null)
    {
        $method = $filter === null ? 'setValue' : 'set';
        if (null !== $entity->getDateDelete()) {
            $query->$method('date_delete', ':date_delete')
                ->setParameter('date_delete', self::formatDateToDb($entity->getDateDelete()));
        } else {
            if (null !== $entity->getClientId()) {
                $query->$method('client_id', ':client_id')->setParameter('client_id', $entity->getClientId());
            } else {
                throw new DatabaseException('client_id must be defined for create or update');
            }
            if (null !== $entity->getUpdaterId()) {
                $query->$method('updater_id', ':updater_id')
                    ->setParameter('updater_id', $entity->getUpdaterId());
            } else {
                throw new DatabaseException('updater_id must be defined for create or update');
            }
            if (null !== $entity->getName()) {
                $query->$method('name', ':name')
                    ->setParameter('name', $entity->getName());
            } else {
                throw new DatabaseException('name must be defined for create or update');
            }
            $query->$method('setting_id', ':setting_id')
                ->setParameter('setting_id', $entity->getParentSettingId());
            $query->$method('project_security_rate', ':project_security_rate')
                ->setParameter('project_security_rate', $entity->getProjectSecurityRate());
            $query->$method('project_currency', ':project_currency')
                ->setParameter('project_currency', $entity->getProjectCurrency());
            $query->$method('men_day_price', ':men_day_price')
                ->setParameter('men_day_price', $entity->getMenDayPrice());
            $query->$method('men_availability_rate', ':men_availability_rate')
                ->setParameter('men_availability_rate', $entity->getMenAvailabilityRate());
            $query->$method('men_availability_absolute', ':men_availability_absolute')
                ->setParameter('men_availability_absolute', $entity->getMenAvailabilityAbsolute());
            $query->$method('management_day_price', ':management_day_price')
                ->setParameter('management_day_price', $entity->getManagementDayPrice());
            $query->$method('management_availability_rate', ':management_availability_rate')
                ->setParameter('management_availability_rate', $entity->getManagementAvailabilityRate());
            $query->$method('management_availability_absolute', ':management_availability_absolute')
                ->setParameter('management_availability_absolute', $entity->getManagementAvailabilityAbsolute());
            $query->$method('display_men_price', ':display_men_price')
                ->setParameter('display_men_price', $entity->isDisplayMenPrice());
            $query->$method('display_management_price', ':display_management_price')
                ->setParameter('display_management_price', $entity->isDisplayManagementPrice());
            $query->$method('display_dates', ':display_dates')
                ->setParameter('display_dates', $entity->isDisplayDates());
            if (null === $entity->getId() || ($filter instanceof SettingFilter && null === $filter->getId())) {
                if (null === $entity->getDateCreate()) {
                    $entity->setDateCreate(new \DateTime());
                }
                $query->$method('date_create', ':date_create')
                    ->setParameter('date_create', self::formatDateToDb($entity->getDateCreate()));
            }
            $query->$method('date_update', ':date_update')
                ->setParameter('date_update', self::formatDateToDb(true));
        }
        if ($filter instanceof SettingFilter) {
            if (null !== $filter->getId()) {
                $query->andWhere('id = :id')
                    ->setParameter('id', $filter->getId());
            }
        }
    }
} 