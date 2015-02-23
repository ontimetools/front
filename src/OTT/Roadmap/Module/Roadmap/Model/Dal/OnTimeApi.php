<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Dal;

use OTT\Api\Connection\ConnectionRequest;
use OTT\Api\Filter\Defects as DefectsFilter;
use OTT\Api\Filter\Features as FeaturesFilter;
use OTT\Api\Filter\Incidents as IncidentsFilter;
use OTT\Api\Filter\ItemAbstract;
use OTT\Api\Filter\Tasks as TasksFilter;
use OTT\Api\Filter\Projects as ProjectsFilter;
use OTT\Api\Filter\Releases as ReleasesFilter;
use OTT\Processor\Helper\Item;
use OTT\Processor\Processor;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Client as ClientEntity;
use OTT\Roadmap\Module\Roadmap\Model\Entity\User as UserEntity;
use OTT\Roadmap\Module\Roadmap\Model\Service\Message\ServiceHeader;

/**
 * Class OnTimeApi
 * @package OTT\Roadmap\Module\Roadmap\Model\Dal
 */
class OnTimeApi extends DalAbstract
{
    /** @var Processor|null */
    private static $processor = null;

    /**
     * @param ServiceHeader $headers
     * @return null|\OTT\Processor\Message
     */
    public static function me(ServiceHeader $headers)
    {
        $result = null;
        if (null === self::getProcessor()) {
            self::connect($headers->getClient(), $headers->getUser());
        }
        if (self::getProcessor() instanceof Processor) {
            $result = self::getProcessor()->me();
            !$result->isSuccess() ?: $result->getResult()->setPassword($headers->getUser()->getPassword());
        }

        return $result;
    }

    /**
     * @param $type
     * @param ServiceHeader $headers
     * @param null $filter
     * @return array
     */
    public static function item($type, ServiceHeader $headers, $filter = null)
    {
        $result = [];
        if (in_array($type, Item::getItemTypes())) {
            if (null === self::getProcessor()) {
                self::connect($headers->getClient(), $headers->getUser());
            }
            if (self::getProcessor() instanceof Processor) {
                if (!$filter instanceof ItemAbstract && null !== $filter) {
                    $filter = null;
                }
                /**
                 * Obligé de faire ça pour récupérer Description et Notes...
                 */
                $message = self::getProcessor()->$type($filter);
                if ($message->isSuccess()) {
                    foreach ($message->getResult() as $item) {
                        $itemMessage = self::getProcessor()->$type($item->getId());
                        if ($itemMessage->isSuccess()) {
                            $item->setDescription($itemMessage->getResult()->getDescription());
                            $item->setNotes($itemMessage->getResult()->getNotes());
                            $result[] = $item;
                        }
                    }
                }
            }

        }

        return $result;
    }

    /**
     * @param ServiceHeader $headers
     * @param null $filter
     * @return null|\OTT\Processor\Message
     */
    public static function projects(ServiceHeader $headers, $filter = null)
    {
        $result = null;
        if (null === self::getProcessor()) {
            self::connect($headers->getClient(), $headers->getUser());
        }
        if (self::getProcessor() instanceof Processor) {
            if (!$filter instanceof ProjectsFilter && null !== $filter) {
                $filter = null;
            }
            $result = self::getProcessor()->projects($filter);
        }

        return $result;
    }

    /**
     * @param ServiceHeader $headers
     * @param null $filter
     * @return null|\OTT\Processor\Message
     */
    public static function releases(ServiceHeader $headers, $filter = null)
    {
        $result = null;
        if (null === self::getProcessor()) {
            self::connect($headers->getClient(), $headers->getUser());
        }
        if (self::getProcessor() instanceof Processor) {
            if (!$filter instanceof ReleasesFilter && null !== $filter) {
                $filter = null;
            }
            $result = self::getProcessor()->releases($filter);
        }

        return $result;
    }

    /**
     * @return null|Processor
     */
    private static function getProcessor()
    {
        return self::$processor instanceof Processor ? self::$processor : null;
    }

    /**
     * @param null|Processor $processor
     */
    private static function setProcessor(Processor $processor)
    {
        self::$processor = $processor;
    }

    /**
     * @param ClientEntity $client
     * @param UserEntity $user
     * @return Processor
     */
    private static function connect(ClientEntity $client, UserEntity $user)
    {
        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setOntimeUrl($client->getOtUrl());
        $connectionRequest->setClientId($client->getOtClientId());
        $connectionRequest->setClientSecret($client->getOtClientSecret());
        $connectionRequest->setUsername($user->getUsername());
        $connectionRequest->setPassword($user->getPassword());
        $processor = new Processor($connectionRequest);
        if (null !== $processor->getOntime()->getToken()) {
            self::setProcessor($processor);
        }
    }
}