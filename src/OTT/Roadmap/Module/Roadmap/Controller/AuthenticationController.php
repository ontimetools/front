<?php

namespace OTT\Roadmap\Module\Roadmap\Controller;

use Guzzle\Http\Message\RequestInterface;
use OTT\Roadmap\Controller\AbstractController;
use OTT\Roadmap\Module\Roadmap\Model\Service\User;
use OTT\Roadmap\Module\Roadmap\Model\Entity\User as UserEntity;

/**
 * Class AuthenticationController
 * @package OTT\Roadmap\Module\Roadmap\Controller
 */
class AuthenticationController extends AbstractController
{
    /**
     * @return mixed
     */
    public function loginAction()
    {
        $this->preload();

        return $this->render();
    }

    /**
     * @return mixed
     */
    public function logoutAction()
    {
        $this->preload();
        User::getInstance()->logoutUser();
        $this->redirect($this->getUrl('index'));
    }

    /**
     * @return mixed
     */
    public function loginPostAction()
    {
        if ($this->request->isMethod(RequestInterface::POST)) {
            $user = new UserEntity();
            $user->setUsername($this->request->request->get('username'));
            $user->setPassword($this->request->request->get('password'));
            $message = User::getInstance()->loginUser($user);
            if (!$message->isSuccess()) {
                $this->addViewParameters('errors', $message->getErrors());
            } else {
                $this->redirect($this->getUrl('index'));
            }
        }
        return $this->render('authentication/login.twig');
    }
}
