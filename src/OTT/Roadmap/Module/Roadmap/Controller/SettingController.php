<?php

namespace OTT\Roadmap\Module\Roadmap\Controller;

use Guzzle\Http\Message\RequestInterface;
use OTT\Roadmap\Controller\AbstractController;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Setting as SettingEntity;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Setting as SettingFilter;
use OTT\Roadmap\Module\Roadmap\Model\Service\Setting;

/**
 * Class SettingController
 * @package OTT\Roadmap\Module\Roadmap\Controller
 */
class SettingController extends AbstractController
{
    /**
     * @return mixed
     */
    public function listAction()
    {
        $this->preload();
        $filter = new SettingFilter();
        $filter->setClientId($this->getClient()->getId());
        $filter->setParentId('');
        $message = Setting::getInstance()->getSettings($filter);
        if ($message->isSuccess()) {
            $this->addViewParameters('settings', $message->getResult());
        }

        return $this->render();
    }

    /**
     * @return mixed
     */
    public function editAction()
    {
        $this->preload();
        $filter = new SettingFilter();
        $filter->setId($this->getRouteParam('id'));
        $filter->setParentId('');
        $filter->setMergedWithParent(false);
        $filter->setClientId($this->getClient()->getId());
        $message = Setting::getInstance()->getSetting($filter);
        if ($message->isSuccess()) {
            $this->addViewParameters('setting', $message->getResult());
        }

        $this->getParentSettings();
        return $this->render();
    }

    /**
     * @return mixed
     */
    public function editPostAction()
    {
        $this->preload();
        $url = $this->getUrl('settings.edit', ['id' => $this->getRouteParam('id')]);
        if ($this->request->isMethod(RequestInterface::POST)) {
            $filter = new SettingFilter();
            $filter->setId($this->getRouteParam('id'));
            $setting = $this->prepareEntity();
            $message = Setting::getInstance()->updateSetting($setting, $filter);
            if (!$message->isSuccess()) {
                $this->addViewParameters('errors', $message->getErrors());
            } else {
                if (null !== $this->getRequest()->request->get('save_leave')) {
                    $url = $this->getUrl('settings');
                }
            }
        }
        $this->redirect($url);
    }

    /**
     * @return mixed
     */
    public function createAction()
    {
        $this->preload();
        $this->getParentSettings();
        return $this->render();
    }

    /**
     * @return mixed
     */
    public function createPostAction()
    {
        $this->preload();
        $url = $this->getUrl('settings');
        if ($this->request->isMethod(RequestInterface::POST)) {
            $setting = $this->prepareEntity();
            $message = Setting::getInstance()->createSetting($setting);
            if ($message->isSuccess()) {
                if (null !== $this->getRequest()->request->get('save_stay')) {
                    $url = $this->getUrl('settings.edit', ['id' => $message->getResult()]);
                }
            }
        }
        $this->redirect($url);
    }

    /**
     * @return mixed
     */
    public function duplicateAction()
    {
        $filter = new SettingFilter();
        $filter->setId($this->getRouteParam('id'));
        $filter->setParentId('');
        $filter->setMergedWithParent(false);
        $filter->setClientId($this->getClient()->getId());
        $message = Setting::getInstance()->getSetting($filter);
        if ($message->isSuccess() && $message->getResult() instanceof SettingEntity) {
            /** @var SettingEntity $entity */
            $entity = $message->getResult();
            $entity->setName('Copy of ' . $entity->getName());
            $entity->setDateCreate(new \DateTime());
            $entity->setDateUpdate(new \DateTime());
            $entity->setDateDelete(null);
            Setting::getInstance()->createSetting($entity);
        }
        $this->redirect($this->getUrl('settings'));
    }

    /**
     * @return mixed
     */
    public function deleteAction()
    {
        $this->preload();
        $filter = new SettingFilter();
        $filter->setId($this->getRouteParam('id'));
        $message = Setting::getInstance()->deleteSetting($filter);
        if (!$message->isSuccess()) {
            $url = $this->getUrl('settings.edit', ['id' => $filter->getId()]);
        } else {
            $url = $this->getUrl('settings');
        }
        $this->redirect($url);
    }

    /**
     *
     */
    private function getParentSettings()
    {
        $filter = new SettingFilter();
        $filter->setClientId($this->getClient()->getId());
        $message = Setting::getInstance()->getSettings($filter);
        if ($message->isSuccess()) {
            $this->addViewParameters('parent_settings', $message->getResult());
        }
    }

    /**
     * @return SettingEntity
     */
    private function prepareEntity()
    {
        $params = $this->getRequest()->request->all();
        $inheritance = $params['inherit'];
        $setting = new SettingEntity();
        $setting->setClientId($this->getClient()->getId());
        $setting->setUpdaterId($this->getUser()->getId());
        $setting->setName(isset($params['name']) ? $params['name'] : null);
        $setting->setParentSettingId($this->handleParameters('setting_id', $params, $inheritance));
        $setting->setProjectSecurityRate($this->handleParameters('project_security_rate', $params, $inheritance));
        $setting->setProjectCurrency($this->handleParameters('project_currency', $params, $inheritance));
        $setting->setMenDayPrice($this->handleParameters('men_day_price', $params, $inheritance));
        $setting->setMenAvailabilityRate($this->handleParameters('men_availability_rate', $params, $inheritance));
        $setting->setMenAvailabilityAbsolute($this->handleParameters('men_availability_absolute', $params, $inheritance));
        $setting->setManagementDayPrice($this->handleParameters('management_day_price', $params, $inheritance));
        $setting->setManagementAvailabilityRate($this->handleParameters('management_availability_rate', $params, $inheritance));
        $setting->setManagementAvailabilityAbsolute($this->handleParameters('management_availability_absolute', $params, $inheritance));
        $setting->setDisplayMenPrice($this->handleParameters('display_men_price', $params, $inheritance));
        $setting->setDisplayManagementPrice($this->handleParameters('display_management_price', $params, $inheritance));
        $setting->setDisplayDates($this->handleParameters('display_dates', $params, $inheritance));

        return $setting;
    }

    /**
     * @param $key
     * @param $source
     * @param $inheritanceSource
     * @return null
     */
    private function handleParameters($key, $source, $inheritanceSource)
    {
        $result = null;
        /** Si la clé existe */
        if (isset($source[$key])) {
            /** Et qu'il n'y a pas d'héritage de configuration */
            if (
                (isset($inheritanceSource[$key]) && $inheritanceSource[$key] == 0) ||
                !isset($inheritanceSource[$key])
            ) {
                $result = $source[$key];
                /** Sinon */
            } else {
                /** S'il y a héritage mais qu'on ne trouve pas de parent */
                if (
                    (isset($source['parent_setting_id']) && null == $source['parent_setting_id']) ||
                    !isset($source['parent_setting_id'])
                ) {
                    $result = $source[$key];
                }
            }
        }
        $result = is_string($result) && strlen($result) > 0 ? $result : null;

        return $result;
    }
}
