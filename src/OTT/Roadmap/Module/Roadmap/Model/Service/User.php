<?php

namespace OTT\Roadmap\Module\Roadmap\Model\Service;

use OTT\Api\Exception\ConnectionException;
use OTT\Roadmap\Module\Roadmap\Model\Dal\OnTimeApi;
use OTT\Roadmap\Module\Roadmap\Model\Service\Message\User as UserMessage;
use OTT\Roadmap\Module\Roadmap\Model\Entity\User as UserEntity;
use OTT\Roadmap\Module\Roadmap\Model\Helper\User as UserHelper;

/**
 * Class User
 * @package OTT\Roadmap\Module\Roadmap\Model\Service
 */
class User extends ServiceAbstract
{
    /**
     * @param UserEntity $request
     * @return UserMessage
     */
    public function loginUser(UserEntity $request)
    {
        $message = new UserMessage();
        try {
            $headers = $this->getHeaders();
            $headers->setUser($request);
            $me = OnTimeApi::me($headers);
            if (null !== $me && $me->isSuccess()) {
                $result = UserHelper::fromDalToEntitySingle($me->getResult());
                $this->getSession()->set('current_user', $result);
                $message->setResult($this->getSession()->get('current_user'));
                $message->setSuccess($this->getSession()->get('current_user') instanceof UserEntity);
            }
        } catch (ConnectionException $e) {
            $message->setErrors($e->getResult());
        }

        return $message;
    }

    /**
     *
     */
    public function logoutUser()
    {
        $this->getSession()->remove('current_user');
    }
}