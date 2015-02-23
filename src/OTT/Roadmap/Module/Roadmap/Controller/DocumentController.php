<?php

namespace OTT\Roadmap\Module\Roadmap\Controller;

use OTT\Roadmap\Controller\AbstractController;
use OTT\Roadmap\Module\Roadmap\Model\Service\Document;
use OTT\Roadmap\Module\Roadmap\Model\Service\Setting as SettingService;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Setting as SettingFilter;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Document as DocumentEntity;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Document as DocumentFilter;

/**
 * Class DocumentController
 * @package OTT\Roadmap\Module\Roadmap\Controller
 */
class DocumentController extends AbstractController
{
    /**
     * @return mixed
     */
    public function listAction()
    {
        $this->preload();
        $filter = new DocumentFilter();
        $filter->setUserId($this->getUser()->getId());
        $filter->setClientId($this->getClient()->getId());
        $message = Document::getInstance()->getDocuments($filter);
        if ($message->isSuccess()) {
            $this->addViewParameters('documents', $message->getResult());
        }
        return $this->render();
    }

    /**
     * @return mixed
     */
    public function readAction()
    {
        $this->preload();
        $filter = new DocumentFilter();
        $filter->setId($this->getRouteParam('id'));
        $filter->setWithEntries(true);
        $filter->setWithSettings(true);
        $filter->setUserId($this->getUser()->getId());
        $filter->setClientId($this->getClient()->getId());
        $message = Document::getInstance()->getDocument($filter);
        if ($message->isSuccess()) {
            $this->addViewParameters('document', $message->getResult());
        }

        return $this->render();
    }

    /**
     * @return mixed
     */
    public function editAction()
    {
        $this->preload();
        $filter = new DocumentFilter();
        $filter->setId($this->getRouteParam('id'));
        $filter->setUserId($this->getUser()->getId());
        $filter->setClientId($this->getClient()->getId());
        $message = Document::getInstance()->getDocument($filter);
        if ($message->isSuccess()) {
            $this->addViewParameters('document', $message->getResult());
        }
        return $this->render();
    }

    /**
     * @return mixed
     */
    public function createAction()
    {
        $this->preload();

        return $this->render();
    }

    /**
     * @return mixed
     */
    public function createPostAction()
    {
        $this->preload();
        $url = $this->getUrl('documents');
        $params = $this->getRequest()->request;
        $entity = new DocumentEntity();
        $entity->setSettingId($params->get('setting_id'));
        $entity->setClientId($this->getClient()->getId());
        $entity->setName($params->get('name'));
        $entity->setKeywords($params->get('keywords'));
        $entity->setDateBegin($params->get('date_start'));
        $entity->setDateEnd($params->get('date_due'));
        $entity->setPrivate(!(bool)$params->get('public'));
        $entity->setOtAuthorId($this->getUser()->getId());
        $entity->setOtUpdaterId($entity->getOtAuthorId());
        $entity->setOtProjectId($params->get('ot_project_id'));
        $entity->setOtReleaseId($params->get('ot_release_id'));
        $filter = new DocumentFilter();
        $filter->setWithDefects(null !== $params->get('ontime_type_defects', null));
        $filter->setWithFeatures(null !== $params->get('ontime_type_features', null));
        $filter->setWithTasks(null !== $params->get('ontime_type_tasks', null));
        $filter->setWithIncidents(null !== $params->get('ontime_type_incidents', null));
        $message = Document::getInstance()->createDocument($entity, $filter);
        if ($message->isSuccess()) {
            if (null !== $this->getRequest()->request->get('save_stay')) {
                $url = $this->getUrl('documents.edit', ['id' => $message->getResult()]);
            }
        }
        $this->redirect($url);
    }

    /**
     * @return mixed
     */
    public function duplicateAction()
    {
        $filter = new DocumentFilter();
        $filter->setId($this->getRouteParam('id'));
        Document::getInstance()->duplicateDocument($filter);
        $this->redirect($this->getUrl('documents'));
    }

    /**
     * @return mixed
     */
    public function deleteAction()
    {
        $this->preload();
        $filter = new DocumentFilter();
        $filter->setId($this->getRouteParam('id'));
        $filter->isWithEntries(true);
        $message = Document::getInstance()->deleteDocument($filter);
        if (!$message->isSuccess()) {
            $url = $this->getUrl('documents.edit', ['id' => $filter->getId()]);
        } else {
            $url = $this->getUrl('documents');
        }
        $this->redirect($url);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProjectsAction()
    {
        $this->preload();
        return $this->encodeResponse(Document::getInstance()->getProjectsForAjax());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getReleasesAction()
    {
        $this->preload();
        return $this->encodeResponse(Document::getInstance()->getReleasesForAjax());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getSettingsAction()
    {
        $this->preload();
        $result = [];
        $filter = new SettingFilter();
        $filter->setActive(true);
        $filter->setClientId($this->getClient()->getId());
        $filter->setParentId('');
        $message = SettingService::getInstance()->getSettings($filter);
        if ($message->isSuccess()) {
            $result = SettingService::getInstance()->getSettingsForAjax($message->getResult());
        }
        return $this->encodeResponse($result);
    }
}
