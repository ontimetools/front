<?php

namespace OTT\Roadmap\Module\Roadmap\Controller;

use OTT\Roadmap\Controller\AbstractController;
use OTT\Roadmap\Module\Roadmap\Model\Service\Document;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Document as DocumentFilter;

/**
 * Class IndexController
 * @package OTT\Roadmap\Module\Roadmap\Controller
 */
class IndexController extends AbstractController
{
    public function indexAction()
    {
        $this->preload();
        $filter = new DocumentFilter();
        $filter->setWithEntries(false);
        $filter->setUserId($this->getUser()->getId());
        $filter->setClientId($this->getClient()->getId());

        $message = Document::getInstance()->getDocuments($filter);
        if ($message->isSuccess()) {
            $this->addViewParameters('latest_documents', $message->getResult());
        }

        $message = Document::getInstance()->getDocuments($filter);
        if ($message->isSuccess()) {
            $this->addViewParameters('favourite_documents', $message->getResult());
        }
        return $this->render();
    }
}
