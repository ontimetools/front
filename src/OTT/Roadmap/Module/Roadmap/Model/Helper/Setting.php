<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Helper;

use OTT\Roadmap\Module\Roadmap\Model\Entity\Setting as SettingEntity;

/**
 * Class Setting
 * @package OTT\Roadmap\Module\Roadmap\Model\Helper
 */
class Setting extends HelperAbstract
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
            null !== self::get($datas, 'id') &&
            null !== self::get($datas, 'client_id')
        ) {
            $entity = null === $element ? new SettingEntity() : $element;
            $entity->setId(self::get($datas, 'id'));
            $entity->setClientId(self::get($datas, 'client_id'));
            $entity->setUpdaterId(self::get($datas, 'updater_id'));
            $entity->setParentSettingId(self::get($datas, 'setting_id'));
            $entity->setName(self::get($datas, 'name'));
            $entity->setProjectSecurityRate(self::get($datas, 'project_security_rate'));
            $entity->setProjectCurrency(self::get($datas, 'project_currency'));
            $entity->setMenDayPrice(self::get($datas, 'men_day_price'));
            $entity->setMenAvailabilityRate(self::get($datas, 'men_availability_rate'));
            $entity->setMenAvailabilityAbsolute(self::get($datas, 'men_availability_absolute'));
            $entity->setManagementDayPrice(self::get($datas, 'management_day_price'));
            $entity->setManagementAvailabilityRate(self::get($datas, 'management_availability_rate'));
            $entity->setManagementAvailabilityAbsolute(self::get($datas, 'management_availability_absolute'));
            $entity->setDisplayMenPrice(self::get($datas, 'display_men_price'));
            $entity->setDisplayManagementPrice(self::get($datas, 'display_management_price'));
            $entity->setDisplayDates(self::get($datas, 'display_dates'));
            $entity->setDateCreate(self::get($datas, 'date_create'));
            $entity->setDateUpdate(self::get($datas, 'date_update'));
            $entity->setDateDelete(self::get($datas, 'date_delete'));
        }

        return $entity;
    }
} 