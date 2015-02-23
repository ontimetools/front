<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Dal;

use Doctrine\DBAL\Query\QueryBuilder;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Document as DocumentEntity;
use OTT\Roadmap\Module\Roadmap\Model\Entity\DocumentEntry as DocumentEntryEntity;
use OTT\Roadmap\Module\Roadmap\Model\Exception\DatabaseException;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Document as DocumentFilter;

/**
 * Class Document
 * @package OTT\Roadmap\Module\Roadmap\Model\Dal
 */
class Document extends DalAbstract
{
    /**
     * @param DocumentFilter $filter
     * @return array
     */
    public static function getDocument(DocumentFilter $filter)
    {
        $query = self::getQb();
        self::prepareDocumentParametersForSelect($query, $filter);
        /** @var \PDOStatement $result */
        $result = $query->execute();

        return $result->fetch();
    }

    /**
     * @param DocumentFilter $filter
     * @return array
     */
    public static function getDocuments(DocumentFilter $filter)
    {
        $query = self::getQb();
        self::prepareDocumentParametersForSelect($query, $filter);
        /** @var \PDOStatement $result */
        $result = $query->execute();

        return $result->fetchAll();
    }

    /**
     * @param DocumentEntity $entity
     * @return null
     * @throws DatabaseException
     * @throws \Exception
     */
    public static function createDocument(DocumentEntity $entity)
    {
        $result = null;
        $query = self::getQb();
        $dbCon = $query->getConnection();
        self::prepareDocumentParameters($query, $entity);
        $dbCon->transactional(function () use ($entity, &$query, &$result) {
            $query->insert(self::DBTABLE_DOCUMENT, 'd')->execute();
            $result = intval(self::getDb()->lastInsertId());
            if (false === $result) {
                throw new DatabaseException('SQL issue: create document.');
            }
        });

        return $result;
    }

    /**
     * @param DocumentEntity $entity
     * @param DocumentFilter $filter
     * @return null
     */
    public static function duplicateDocument(DocumentEntity $entity, DocumentFilter $filter)
    {
        $result = self::createDocument($entity);
        if (is_int($result) && $filter->isWithEntries()) {
            $filter = new DocumentFilter();
            $filter->setId($result);
            self::createDocumentEntries($entity->getEntries(), $filter);
        }

        return $result;
    }

    /**
     * @param DocumentFilter $filter
     * @return bool
     */
    public static function deleteDocument(DocumentFilter $filter)
    {
        $entity = new DocumentEntity();
        $entity->setDateDelete(new \DateTime());
        $result = self::updateDocument($entity, $filter);
        if ($result && $filter->isWithEntries()) {
            self::deleteDocumentEntry($filter);
        }

        return $result;
    }

    /**
     * @param DocumentEntity $entity
     * @param DocumentFilter $filter
     * @return bool
     * @throws \Exception
     */
    public static function updateDocument(DocumentEntity $entity, DocumentFilter $filter)
    {
        $result = false;
        $query = self::getQb();
        $dbCon = $query->getConnection();
        self::prepareDocumentParameters($query, $entity, $filter);
        $dbCon->transactional(function () use ($entity, $filter, &$query, &$result) {
            $retUpdate = $query->update(self::DBTABLE_DOCUMENT, 'd')->execute();
            if (false === $retUpdate) {
                throw new DatabaseException('SQL issue: update document.');
            }
            $result = true;
        });

        return $result;
    }


    /**
     * @param QueryBuilder $query
     * @param DocumentFilter $filter
     */
    private static function prepareDocumentParametersForSelect(QueryBuilder &$query, DocumentFilter $filter)
    {
        $query->select('*')
            ->from(self::DBTABLE_DOCUMENT)
            ->orderBy('date_update', 'DESC');
        if (null !== $filter->getId()) {
            $query->where('id = :id')
                ->setParameter('id', $filter->getId());
        }
        if (null !== $filter->isActive()) {
            $suffix = $filter->isActive() ? 'NULL' : 'NOT NULL';
            $query->andWhere('date_delete IS ' . $suffix);
        }
        if (null !== $filter->getClientId()) {
            $query->andWhere('client_id = :client_id')->setParameter('client_id', $filter->getClientId());
        }
        if (null !== $filter->getUserId()) {
        }
    }

    /**
     * @param QueryBuilder $query
     * @param DocumentEntity $entity
     * @param DocumentFilter $filter
     * @throws DatabaseException
     */
    private static function prepareDocumentParameters(
        QueryBuilder &$query,
        DocumentEntity $entity,
        DocumentFilter $filter = null
    )
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
            if (null !== $entity->getName()) {
                $query->$method('name', ':name')->setParameter('name', $entity->getName());
            } else {
                throw new DatabaseException('name must be defined for create or update');
            }
            if (null !== $entity->isPrivate()) {
                $query->$method('private', ':private')->setParameter('private', $entity->isPrivate());
            } else {
                throw new DatabaseException('private must be defined for create or update');
            }
            if (null !== $entity->getOtUpdaterId()) {
                $query->$method('ot_updater_id', ':ot_updater_id')
                    ->setParameter('ot_updater_id', $entity->getOtUpdaterId());
            } else {
                throw new DatabaseException('ot_updater_id must be defined for create or update');
            }
            $query->$method('setting_id', ':setting_id')
                ->setParameter('setting_id', $entity->getSettingId());
            $query->$method('keywords', ':keywords')
                ->setParameter('keywords', $entity->getKeywords());
            $query->$method('date_begin', ':date_begin')
                ->setParameter('date_begin', self::formatDateToDb($entity->getDateBegin()));
            $query->$method('date_end', ':date_end')
                ->setParameter('date_end', self::formatDateToDb($entity->getDateBegin()));
            $query->$method('ot_author_id', ':ot_author_id')
                ->setParameter('ot_author_id', $entity->getOtAuthorId());
            $query->$method('ot_project_id', ':ot_project_id')
                ->setParameter('ot_project_id', $entity->getOtProjectId());
            $query->$method('ot_release_id', ':ot_release_id')
                ->setParameter('ot_release_id', $entity->getOtReleaseId());
            $query->$method('ot_customer_id', ':ot_customer_id')
                ->setParameter('ot_customer_id', $entity->getOtCustomerId());
            $query->$method('ot_type', ':ot_type')
                ->setParameter('ot_type', $entity->getOtType());
            $query->$method('ot_status', ':ot_status')
                ->setParameter('ot_status', $entity->getOtStatus());
            if (null === $entity->getId() || ($filter instanceof DocumentFilter && null === $filter->getId())) {
                if (null === $entity->getDateCreate()) {
                    $entity->setDateCreate(new \DateTime());
                }
                $query->$method('date_create', ':date_create')
                    ->setParameter('date_create', self::formatDateToDb($entity->getDateCreate()));
            }
            $query->$method('date_update', ':date_update')
                ->setParameter('date_update', self::formatDateToDb(true));
        }
        if ($filter instanceof DocumentFilter) {
            if (null !== $filter->getId()) {
                $query->andWhere('id = :id')
                    ->setParameter('id', $filter->getId());
            }
        }
    }
    /**
     * @param DocumentFilter $filter
     * @return array
     */
    public static function getDocumentEntry(DocumentFilter $filter)
    {
        $query = self::getQb();
        self::prepareDocumentEntryParametersForSelect($query, $filter);
        /** @var \PDOStatement $result */
        $result = $query->execute();

        return $result->fetch();
    }

    /**
     * @param DocumentFilter $filter
     * @return array
     */
    public static function getDocumentEntries(DocumentFilter $filter)
    {
        $query = self::getQb();
        self::prepareDocumentEntryParametersForSelect($query, $filter);
        /** @var \PDOStatement $result */
        $result = $query->execute();

        return $result->fetchAll();
    }

    /**
     * @param array $entities
     * @param DocumentFilter $filter
     */
    public static function createDocumentEntries(array $entities = [], DocumentFilter $filter = null)
    {
        /** @var DocumentEntryEntity $entity */
        foreach ($entities as $entity) {
            if (null !== $filter && null !== $filter->getId()) {
                $entity->setDocumentId($filter->getId());
                $entity->setId(null);
                $entity->setDateCreate(null);
            }
            self::createDocumentEntry($entity);
        }
    }

    /**
     * @param DocumentEntryEntity $entity
     * @return int
     */
    public static function createDocumentEntry(DocumentEntryEntity $entity)
    {
        $result = null;
        $query = self::getQb();
        $dbCon = $query->getConnection();
        self::prepareDocumentEntryParameters($query, $entity);
        $dbCon->transactional(function () use ($entity, &$query, &$result) {
            $query->insert(self::DBTABLE_DOCUMENT_ENTRY, 'de')->execute();
            $result = intval(self::getDb()->lastInsertId());
            if (false === $result) {
                throw new DatabaseException('SQL issue: create document entry.');
            }
        });

        return $result;
    }

    /**
     * @param DocumentFilter $filter
     * @return bool
     */
    public static function deleteDocumentEntry(DocumentFilter $filter)
    {
        $entity = new DocumentEntryEntity();
        $entity->setDateDelete(new \DateTime());
        $result = self::updateDocumentEntry($entity, $filter);

        return $result;
    }

    /**
     * @param DocumentEntryEntity $entity
     * @param DocumentFilter $filter
     * @return bool
     * @throws DatabaseException
     * @throws \Exception
     */
    public static function updateDocumentEntry(DocumentEntryEntity $entity, DocumentFilter $filter)
    {
        $result = false;
        $query = self::getQb();
        $dbCon = $query->getConnection();
        self::prepareDocumentEntryParameters($query, $entity, $filter);
        $dbCon->transactional(function () use ($entity, $filter, &$query, &$result) {
            $retUpdate = $query->update(self::DBTABLE_DOCUMENT_ENTRY, 'de')->execute();
            if (false === $retUpdate) {
                throw new DatabaseException('SQL issue: update document entry.');
            }
            $result = true;
        });

        return $result;
    }


    /**
     * @param QueryBuilder $query
     * @param DocumentFilter $filter
     */
    private static function prepareDocumentEntryParametersForSelect(QueryBuilder &$query, DocumentFilter $filter)
    {
        $query->select('*')
            ->from(self::DBTABLE_DOCUMENT_ENTRY)
            ->orderBy('ot_id, type', 'ASC');
        if (null !== $filter->getEntryId()) {
            $query->andWhere('id = :id')
                ->setParameter('id', $filter->getEntryId());
        }
        if (null !== $filter->getId()) {
            $query->andWhere('document_id = :document_id')
                ->setParameter('document_id', $filter->getId());
        }
        if (null !== $filter->isActive()) {
            $suffix = $filter->isActive() ? 'NULL' : 'NOT NULL';
            $query->andWhere('date_delete IS ' . $suffix);
        }
    }

    /**
     * @param QueryBuilder $query
     * @param DocumentEntryEntity $entity
     * @param DocumentFilter $filter
     * @throws DatabaseException
     */
    private static function prepareDocumentEntryParameters(
        QueryBuilder &$query,
        DocumentEntryEntity $entity,
        DocumentFilter $filter = null
    )
    {
        $method = $filter === null ? 'setValue' : 'set';
        if (null !== $entity->getDateDelete()) {
            $query->$method('date_delete', ':date_delete')
                ->setParameter('date_delete', self::formatDateToDb($entity->getDateDelete()));
        } else {
            if (null !== $entity->getOtId()) {
                $query->$method('ot_id', ':ot_id')->setParameter('ot_id', $entity->getOtId());
            } else {
                throw new DatabaseException('ot_id must be defined for create or update for item');
            }
            if (null !== $entity->getType()) {
                $query->$method('type', ':type')->setParameter('type', $entity->getType());
            } else {
                throw new DatabaseException(
                    'type must be defined for create or update for item #' . $entity->getOtId()
                );
            }
            if (null !== $entity->getDocumentId()) {
                $query->$method('document_id', ':document_id')->setParameter('document_id', $entity->getDocumentId());
            } else {
                throw new DatabaseException(
                    'document_id must be defined for create or update for ' .
                    $entity->getType() . ' #' . $entity->getOtId()
                );
            }
            if (null !== $entity->getName()) {
                $query->$method('name', ':name')->setParameter('name', $entity->getName());
            } else {
                throw new DatabaseException(
                    'name must be defined for create or update for ' .
                    $entity->getType() . ' #' . $entity->getOtId()
                );
            }
            if (null !== $entity->getDescriptionShort()) {
                $query->$method('description_short', ':description_short')
                    ->setParameter('description_short', $entity->getDescriptionShort());
            } else {
                throw new DatabaseException(
                    'description_short must be defined for create or update for ' .
                    $entity->getType() . ' #' . $entity->getOtId()
                );
            }
            if (null !== $entity->getDescriptionComplete()) {
                $query->$method('description_complete', ':description_complete')
                    ->setParameter('description_complete', $entity->getDescriptionComplete());
            } else {
                throw new DatabaseException(
                    'description_complete must be defined for create or update for ' .
                    $entity->getType() . ' #' . $entity->getOtId()
                );
            }
            $query->$method('entry_id', ':entry_id')
                ->setParameter('entry_id', $entity->getEntryId());
            $query->$method('project_name', ':project_name')
                ->setParameter('project_name', $entity->getProjectName());
            $query->$method('project_parent_name', ':project_parent_name')
                ->setParameter('project_parent_name', $entity->getProjectParentName());
            $query->$method('estimate', ':estimate')
                ->setParameter('estimate', $entity->getEstimate());
            $query->$method('remaining', ':remaining')
                ->setParameter('remaining', $entity->getRemaining());
            $query->$method('actual', ':actual')
                ->setParameter('actual', $entity->getActual());
            $query->$method('estimate_unit', ':estimate_unit')
                ->setParameter('estimate_unit', $entity->getEstimateUnit());
            $query->$method('percent_complete', ':percent_complete')
                ->setParameter('percent_complete', $entity->getPercentComplete());
            $query->$method('priority_name', ':priority_name')
                ->setParameter('priority_name', $entity->getPriorityName());
            if (null === $entity->getId() || ($filter instanceof DocumentFilter && null === $filter->getId())) {
                if (null === $entity->getDateCreate()) {
                    $entity->setDateCreate(new \DateTime());
                }
                $query->$method('date_create', ':date_create')
                    ->setParameter('date_create', self::formatDateToDb($entity->getDateCreate()));
            }
            $query->$method('date_update', ':date_update')
                ->setParameter('date_update', self::formatDateToDb(true));
        }
        if ($filter instanceof DocumentFilter) {
            if (null !== $filter->getId()) {
                $query->andWhere('document_id = :document_id')
                    ->setParameter('document_id', $filter->getId());
            }
        }
        if ($filter instanceof DocumentFilter) {
            if (null !== $filter->getEntryId()) {
                $query->andWhere('id = :id')
                    ->setParameter('id', $filter->getEntryId());
            }
        }
    }
} 