<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Service\Message;

use OTT\Roadmap\Module\Roadmap\Model\Entity\Client as ClientEntity;
use OTT\Roadmap\Module\Roadmap\Model\Entity\User as UserEntity;

/**
 * Class ServiceHeader
 * @package OTT\Roadmap\Module\Roadmap\Model\Service\Message
 */
class ServiceHeader extends MessageAbstract
{
    /** @var ClientEntity */
    protected $client;
    /** @var UserEntity */
    protected $user;
    /** @var null */
    protected $locale = null;

    /**
     * @return ClientEntity
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return null
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param null $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserEntity $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}