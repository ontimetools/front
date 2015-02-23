<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Helper;

use OTT\Roadmap\Module\Roadmap\Model\Entity\Client as ClientEntity;

/**
 * Class Client
 * @package OTT\Roadmap\Module\Roadmap\Model\Helper
 */
class Client extends HelperAbstract
{
    /**
     * @param array $datas
     * @param null $element
     * @return mixed
     */
    public static function fromDalToEntitySingle($datas = [], &$element = null)
    {
        $entity = null;
        if (
            null !== self::get($datas, 'ot_url') &&
            null !== self::get($datas, 'ot_client_id') &&
            null !== self::get($datas, 'ot_client_secret')
        ) {
            $entity = null === $element ? new ClientEntity() : $element;
            $entity->setId(self::get($datas, 'id'));
            $entity->setHourPerDay(self::get($datas, 'setting_hour_per_day'));
            $entity->setDayPerWeek(self::get($datas, 'setting_day_per_week'));
            $entity->setWeekPerIteration(self::get($datas, 'setting_week_per_iteration'));
            $entity->setSubdomain(self::get($datas, 'subdomain'));
            $entity->setOtUrl(self::get($datas, 'ot_url'));
            $entity->setOtClientId(self::get($datas, 'ot_client_id'));
            $entity->setOtClientSecret(self::get($datas, 'ot_client_secret'));
            $entity->setDateCreate(self::get($datas, 'date_create'));
            $entity->setDateUpdate(self::get($datas, 'date_update'));
            $entity->setDateDelete(self::get($datas, 'date_delete'));
        }

        return $entity;
    }
} 