<?php

namespace OTT\Roadmap\Module\Roadmap\Model\Service;

use OTT\Roadmap\Module\Roadmap\Model\Exception\DatabaseException;
use OTT\Roadmap\Module\Roadmap\Model\Service\Message\Client as ClientMessage;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Client as ClientFilter;
use OTT\Roadmap\Module\Roadmap\Model\Helper\Client as ClientHelper;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Client as ClientEntity;
use OTT\Roadmap\Module\Roadmap\Model\Dal\Client as ClientDal;

/**
 * Class Client
 * @package OTT\Roadmap\Module\Roadmap\Model\Service
 */
class Client extends ServiceAbstract
{
    /**
     * @param ClientFilter $filter
     * @return ClientMessage
     */
    public function getClient(ClientFilter $filter)
    {
        $message = new ClientMessage();
        try {
            $result = ClientHelper::fromDalToEntitySingle(
                ClientDal::getClient($filter)
            );
            if ($result instanceof ClientEntity) {
                $message->setSuccess(true);
                $message->setResult($result);
            } else {
                $message->setErrors(['Client not found.']);
            }
        } catch (DatabaseException $e) {
            $message->setErrors([$e->getMessage()]);
        }

        return $message;
    }

    /**
     * @param ClientEntity $entity
     * @param ClientFilter $filter
     * @return ClientMessage
     */
    public function updateClient(ClientEntity $entity, ClientFilter $filter)
    {
        $message = new ClientMessage();
        try {
            $message->setResult(ClientDal::updateClient($entity, $filter));
            $message->setSuccess($message->getResult());
        } catch (DatabaseException $e) {
            $message->setErrors([$e->getMessage()]);
        }

        return $message;
    }

    /**
     * @param ClientFilter $filter
     * @return ClientMessage
     */
    public function deleteClient(ClientFilter $filter)
    {
        $message = new ClientMessage();
        try {
            $message->setResult(ClientDal::deleteClient($filter));
            $message->setSuccess($message->getResult());
        } catch (DatabaseException $e) {
            $message->setErrors([$e->getMessage()]);
        }

        return $message;
    }
}