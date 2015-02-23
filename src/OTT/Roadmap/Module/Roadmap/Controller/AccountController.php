<?php

namespace OTT\Roadmap\Module\Roadmap\Controller;

use Guzzle\Http\Message\RequestInterface;
use OTT\Roadmap\Controller\AbstractController;
use OTT\Roadmap\Module\Roadmap\Model\Service\Client;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Client as ClientFilter;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Client as ClientEntity;

/**
 * Class AccountController
 * @package OTT\Roadmap\Module\Roadmap\Controller
 */
class AccountController extends AbstractController
{
    /**
     * @return mixed
     */
    public function accountAction()
    {
        $this->preload();

        return $this->render();
    }

    /**
     * @return mixed
     */
    public function accountEditAction()
    {
        $this->preload();
        if ($this->request->isMethod(RequestInterface::POST)) {
            $filter = new ClientFilter();
            $filter->setId($this->getClient()->getId());
            $client = new ClientEntity();
            $client->setHourPerDay($this->request->request->get('hours_per_day'));
            $client->setDayPerWeek($this->request->request->get('days_per_week'));
            $client->setWeekPerIteration($this->request->request->get('week_per_iteration'));
            $message = Client::getInstance()->updateClient($client, $filter);
            if (!$message->isSuccess()) {
                $this->addViewParameters('errors', $message->getErrors());
            } else {
                $this->redirect($this->getUrl('account'));
            }
        }
        return $this->render('account/account.twig');
    }

    /**
     * @return mixed
     */
    public function accountDeleteAction()
    {
        $this->preload();
        if ($this->request->isMethod(RequestInterface::GET)) {
            $filter = new ClientFilter();
            $filter->setId($this->getClient()->getId());
            $message = Client::getInstance()->deleteClient($filter);
            if (!$message->isSuccess()) {
                $this->addViewParameters('errors', $message->getErrors());
            } else {
                $this->redirect($this->getUrl('index.home'));
            }
        }
        return $this->render('account/account.twig');
    }
}
