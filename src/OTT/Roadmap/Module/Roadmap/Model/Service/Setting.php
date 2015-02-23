<?php

namespace OTT\Roadmap\Module\Roadmap\Model\Service;

use OTT\Roadmap\Module\Roadmap\Model\Entity\Setting as SettingEntity;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Setting as SettingFilter;
use OTT\Roadmap\Module\Roadmap\Model\Exception\DatabaseException;
use OTT\Roadmap\Module\Roadmap\Model\Service\Message\Setting as SettingMessage;
use OTT\Roadmap\Module\Roadmap\Model\Dal\Setting as SettingDal;
use OTT\Roadmap\Module\Roadmap\Model\Helper\Setting as SettingHelper;

/**
 * Class Setting
 * @package OTT\Roadmap\Module\Roadmap\Model\Service
 */
class Setting extends ServiceAbstract
{
    /**
     * @param SettingFilter $filter
     * @return SettingMessage
     */
    public function getSetting(SettingFilter $filter)
    {
        $message = new SettingMessage();
        try {
            $message->setResult(
                SettingHelper::fromDalToEntitySingle(
                    SettingDal::getSetting($filter)
                )
            );
            $message->setSuccess($message->getResult() instanceof SettingEntity);
        } catch (DatabaseException $e) {
            $message->setErrors([$e->getMessage()]);
        }

        return $message;
    }

    /**
     * @param SettingFilter $filter
     * @return SettingMessage
     */
    public function getSettings(SettingFilter $filter)
    {
        $message = new SettingMessage();
        try {
            $message->setResult(
                SettingHelper::fromDalToEntity(
                    SettingDal::getSettings($filter)
                )
            );
            $message->setSuccess(is_array($message->getResult()));
        } catch (DatabaseException $e) {
            $message->setErrors([$e->getMessage()]);
        }

        return $message;
    }

    /**
     * @param null $settings
     * @return array
     */
    public function getSettingsForAjax($settings = null)
    {
        $result = [];
        /** @var SettingEntity $setting */
        foreach ($settings as $key => $setting) {
            $result[$setting->getId()]['id'] = $setting->getId();
            $result[$setting->getId()]['name'] = $setting->getName();
        }
        return $result;
    }

    /**
     * @param SettingEntity $entity
     * @return SettingMessage
     */
    public function createSetting(SettingEntity $entity)
    {
        $message = new SettingMessage();
        try {
            $message->setResult(SettingDal::createSetting($entity));
            $message->setSuccess(is_int($message->getResult()));
        } catch (DatabaseException $e) {
            $message->setErrors([$e->getMessage()]);
        }

        return $message;
    }

    /**
     * @param SettingEntity $entity
     * @param SettingFilter $filter
     * @return SettingMessage
     */
    public function updateSetting(SettingEntity $entity, SettingFilter $filter)
    {
        $message = new SettingMessage();
        try {
            $message->setResult(SettingDal::updateSetting($entity, $filter));
            $message->setSuccess($message->getResult() === true);
        } catch (DatabaseException $e) {
            $message->setErrors([$e->getMessage()]);
        }

        return $message;
    }

    /**
     * @param SettingFilter $filter
     * @return SettingMessage
     */
    public function deleteSetting(SettingFilter $filter)
    {
        $message = new SettingMessage();
        try {
            $message->setResult(SettingDal::deleteSetting($filter));
            $message->setSuccess($message->getResult() === true);
        } catch (DatabaseException $e) {
            $message->setErrors([$e->getMessage()]);
        }

        return $message;
    }
}