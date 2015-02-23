<?php

namespace OTT\Roadmap\Module\Roadmap\Model\Service;

use OTT\Roadmap\Module\Roadmap\Model\Entity\User as UserEntity;
use OTT\Roadmap\Module\Roadmap\Model\Service\Message\User as UserMessage;
use OTT\Roadmap\Server;
use OTT\Roadmap\Module\Roadmap\Model\Service\Message\Client as ClientMessage;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Client as ClientFilter;

/**
 * Class Application
 * @package OTT\Roadmap\Module\Roadmap\Model\Service
 */
class Application extends ServiceAbstract
{
    /**
     * @return UserMessage
     */
    public function getCurrentUser()
    {
        $session = Server::getService('session');
        $user = $session->get('current_user');
        $message = new UserMessage();
        if ($user instanceof UserEntity) {
            $message->setResult($user);
            $message->setSuccess(true);
        }

        return $message;
    }

    /**
     * @return ClientMessage
     */
    public function getCurrentClient()
    {
        $filter = new ClientFilter();
        $filter->setSubdomain($this->getClientSubdomain());
        $message = Client::getInstance()->getClient($filter);

        return $message;
    }

    /**
     *
     */
    protected function getClientSubdomain()
    {
        $result = null;
        $baseUrl = Server::getService('conf.app')['silex']['modules'][0]['url'];
        $requestedUrl = Server::getService('request')->getHost();
        if ($baseUrl !== $requestedUrl) {
            $result = str_replace('.' . $baseUrl, '', $requestedUrl);
        }
        return $result;
    }
}