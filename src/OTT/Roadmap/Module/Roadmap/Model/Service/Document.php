<?php

namespace OTT\Roadmap\Module\Roadmap\Model\Service;

use OTT\Api\Filter\Defects;
use OTT\Api\Filter\Features;
use OTT\Api\Filter\Incidents;
use OTT\Api\Filter\Releases as ReleasesFilter;
use OTT\Api\Filter\Tasks;
use OTT\Processor\Entity\Release as ReleaseEntity;
use OTT\Api\Filter\Projects as ProjectsFilter;
use OTT\Processor\Entity\Project as ProjectEntity;
use OTT\Processor\Helper\Item;
use OTT\Roadmap\Module\Roadmap\Model\Exception\DatabaseException;
use OTT\Roadmap\Module\Roadmap\Model\Helper\Setting;
use OTT\Roadmap\Module\Roadmap\Model\Service\Message\Document as DocumentMessage;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Document as DocumentEntity;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Document as DocumentFilter;
use OTT\Roadmap\Module\Roadmap\Model\Helper\Document as DocumentHelper;
use OTT\Roadmap\Module\Roadmap\Model\Dal\Document as DocumentDal;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Setting as SettingFilter;
use OTT\Roadmap\Module\Roadmap\Model\Dal\Setting as SettingDal;
use OTT\Roadmap\Module\Roadmap\Model\Helper\Setting as SettingHelper;
use OTT\Roadmap\Module\Roadmap\Model\Dal\OnTimeApi;

/**
 * Class Document
 * @package OTT\Roadmap\Module\Roadmap\Model\Service
 */
class Document extends ServiceAbstract
{
    /**
     * @param DocumentEntity $entity
     * @param DocumentFilter $filter
     * @return DocumentMessage
     */
    public function createDocument(DocumentEntity $entity, DocumentFilter $filter)
    {
        $message = new DocumentMessage();
        if (null !== $entity->getOtReleaseId()) {
            $releaseFilter = new ReleasesFilter();
            $releaseFilter->setId($entity->getOtReleaseId());
            $releaseMessage = $this->getReleases($releaseFilter);
            if ($releaseMessage->isSuccess()) {
                /** @var ReleaseEntity $release */
                $release = $releaseMessage->getResult();
                $entity->setOtType($release->getReleaseType()->getName());
                $entity->setOtStatus($release->getStatus()->getName());
            }
        }
        $documentId = DocumentDal::createDocument($entity);
        $message->setSuccess(is_int($documentId));
        if ($message->isSuccess()) {
            $message->setResult($documentId);
            $filter->setId($documentId);
            $this->createEntries($entity, $filter);
        }

        return $message;
    }

    /**
     * @param DocumentFilter $filter
     * @return DocumentMessage
     */
    public function duplicateDocument(DocumentFilter $filter)
    {
        $message = new DocumentMessage();
        if (null !== $filter->getId()) {
            $filter->setClientId($this->getHeaders()->getClient()->getId());
            $filter->isWithEntries(true);
            $docMsg = Document::getInstance()->getDocument($filter);
            if ($docMsg->isSuccess() && $docMsg->getResult() instanceof DocumentEntity) {
                /** @var DocumentEntity $entity */
                $entity = $docMsg->getResult();
                $entity->setName('Copy of ' . $entity->getName());
                $entity->setId(null);
                $entity->setDateCreate(null);
                $entity->setOtAuthorId($this->getHeaders()->getUser()->getId());
                $docId = DocumentDal::duplicateDocument($entity, $filter);
                $message->setResult($docId);
                $message->setSuccess(is_int($docId));
            }
        }

        return $message;
    }

    /**
     * @param DocumentFilter $filter
     * @return DocumentMessage
     */
    public function getDocuments(DocumentFilter $filter)
    {
        $message = new DocumentMessage();
        try {
            $message->setResult(DocumentHelper::fromDalToEntity(DocumentDal::getDocuments($filter)));
            $message->setSuccess(is_array($message->getResult()));
        } catch (DatabaseException $e) {
            $message->setErrors([$e->getMessage()]);
        }

        return $message;
    }

    /**
     * @param DocumentFilter $filter
     * @return DocumentMessage
     */
    public function getDocument(DocumentFilter $filter)
    {
        $message = new DocumentMessage();
        try {
            $result = DocumentHelper::fromDalToEntitySingle(DocumentDal::getDocument($filter));
            $message->setSuccess($result instanceof DocumentEntity);
            $isSuccess = $message->isSuccess();
            $settings = null;
            if ($isSuccess) {
                if ($filter->isWithSettings() && null !== $result->getSettingId()) {
                    $settingFilter = new SettingFilter();
                    $settingFilter->setId($result->getSettingId());
                    $settings = SettingHelper::fromDalToEntitySingle(SettingDal::getSetting($settingFilter));
                }
                if ($filter->isWithEntries()) {
                    $result->setEntries(
                        DocumentHelper::fromDalToEntityEntries(
                            DocumentDal::getDocumentEntries($filter),
                            $this->getHeaders()->getClient(),
                            $settings
                        )
                    );
                }
                $message->setResult($result);
            }
        } catch (DatabaseException $e) {
            $message->setErrors([$e->getMessage()]);
        }

        return $message;
    }

    /**
     * @param DocumentEntity $entity
     * @param DocumentFilter $documentFilter
     */
    private function createEntries(DocumentEntity $entity, DocumentFilter $documentFilter)
    {
        if ($documentFilter->isWithDefects()) {
            $filter = new Defects();
            $filter->setProjectId($entity->getOtProjectId());
            $filter->setReleaseId($entity->getOtReleaseId());
            $items = OnTimeApi::item(Item::ITEMTYPE_DEFECTS, $this->getHeaders(), $filter);
            DocumentDal::createDocumentEntries(DocumentHelper::itemFromApiToEntityEntry($items, $documentFilter));
        }
        if ($documentFilter->isWithFeatures()) {
            $filter = new Features();
            $filter->setProjectId($entity->getOtProjectId());
            $filter->setReleaseId($entity->getOtReleaseId());
            $items = OnTimeApi::item(Item::ITEMTYPE_FEATURES, $this->getHeaders(), $filter);
            DocumentDal::createDocumentEntries(DocumentHelper::itemFromApiToEntityEntry($items, $documentFilter));
        }
        if ($documentFilter->isWithIncidents()) {
            $filter = new Incidents();
            $filter->setProjectId($entity->getOtProjectId());
            $filter->setReleaseId($entity->getOtReleaseId());
            $items = OnTimeApi::item(Item::ITEMTYPE_INCIDENTS, $this->getHeaders(), $filter);
            DocumentDal::createDocumentEntries(DocumentHelper::itemFromApiToEntityEntry($items, $documentFilter));
        }
        if ($documentFilter->isWithTasks()) {
            $filter = new Tasks();
            $filter->setProjectId($entity->getOtProjectId());
            $filter->setReleaseId($entity->getOtReleaseId());
            $items = OnTimeApi::item(Item::ITEMTYPE_TASKS, $this->getHeaders(), $filter);
            DocumentDal::createDocumentEntries(DocumentHelper::itemFromApiToEntityEntry($items, $documentFilter));
        }
    }

    /**
     * @param DocumentFilter $filter
     * @return DocumentMessage
     */
    public function deleteDocument(DocumentFilter $filter)
    {
        $message = new DocumentMessage();
        try {
            $message->setResult(DocumentDal::deleteDocument($filter));
            $message->setSuccess($message->getResult() === true);
        } catch (DatabaseException $e) {
            $message->setErrors([$e->getMessage()]);
        }

        return $message;
    }

    /**
     * @param ProjectsFilter $filter
     * @return DocumentMessage
     */
    public function getProjects(ProjectsFilter $filter = null)
    {
        $message = new DocumentMessage();
        $projects = OnTimeApi::projects($this->getHeaders(), $filter);
        $message->setSuccess($projects->isSuccess());
        $message->setResult($projects->getResult());
        $message->setErrors($projects->getErrors());

        return $message;
    }

    /**
     * @param ReleasesFilter $filter
     * @return DocumentMessage
     */
    public function getReleases(ReleasesFilter $filter = null)
    {
        $message = new DocumentMessage();
        $projects = OnTimeApi::releases($this->getHeaders(), $filter);
        $message->setSuccess($projects->isSuccess());
        $message->setResult($projects->getResult());
        $message->setErrors($projects->getErrors());

        return $message;
    }

    /**
     * @param null $projects
     * @param int $level
     * @param array $result
     * @return array
     */
    public function getProjectsForAjax($projects = null, $level = 1, &$result = [])
    {
        if (null === $projects) {
            $message = $this->getProjects();
            if ($message->isSuccess()) {
                $projects = $message->getResult();
            }
        }
        /** @var ProjectEntity $project */
        foreach ($projects as $key => $project) {
            $prefix = null;
            for ($i = 0; $i < $level; $i++) {
                $prefix .= '-';
            }
            $index = count($result);
            $result[$index]['id'] = $project->getId();
            $result[$index]['name'] = $prefix . ' ' . $project->getName();
            if (count($project->getChildren()) > 0) {
                $this->getProjectsForAjax($project->getChildren(), $level + 1, $result);
            }
        }
        return $result;
    }

    /**
     * @param null $ReleasesFilter
     * @param int $level
     * @param array $result
     * @return array
     */
    public function getReleasesForAjax($ReleasesFilter = null, $level = 1, &$result = [])
    {
        if (null === $ReleasesFilter) {
            $message = $this->getReleases();
            if ($message->isSuccess()) {
                $ReleasesFilter = $message->getResult();
            }
        }
        /** @var ReleaseEntity $release */
        foreach ($ReleasesFilter as $key => $release) {
            $prefix = null;
            for ($i = 0; $i < $level; $i++) {
                $prefix .= '-';
            }
            $index = count($result);
            $result[$index]['id'] = $release->getId();
            $result[$index]['name'] = $prefix . ' ' . $release->getName();
            $result[$index]['date_start'] = $release->getStartDate() instanceof \DateTime ?
                $release->getStartDate()->format('Y-m-d') : null;
            $result[$index]['date_due'] = $release->getDueDate() instanceof \DateTime ?
                $release->getDueDate()->format('Y-m-d') : null;
            if (count($release->getChildren()) > 0) {
                $this->getReleasesForAjax($release->getChildren(), $level + 1, $result);
            }
        }
        return $result;
    }
} 