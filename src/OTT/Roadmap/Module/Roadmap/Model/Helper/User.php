<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Helper;

use OTT\Roadmap\Module\Roadmap\Model\Entity\User as UserEntity;
use OTT\Processor\Entity\User as ProcessorUserEntity;

/**
 * Class User
 * @package OTT\Roadmap\Module\Roadmap\Model\Helper
 */
class User extends HelperAbstract
{
    /**
     * @param ProcessorUserEntity $processor
     * @param null $element
     * @return null|UserEntity
     */
    public static function fromDalToEntitySingle(ProcessorUserEntity $processor, &$element = null)
    {
        $entity = null === $element ? new UserEntity() : $element;
        $entity->setId($processor->getId());
        $entity->setUsername($processor->getLoginId());
        $entity->setPassword($processor->getPassword());
        $entity->setFirstname($processor->getFirstname());
        $entity->setLastname($processor->getLastname());

        return $entity;
    }
} 