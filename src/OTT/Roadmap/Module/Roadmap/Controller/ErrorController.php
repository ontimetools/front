<?php

namespace OTT\Roadmap\Module\Roadmap\Controller;

use OTT\Roadmap\Controller\AbstractController;

/**
 * Class ErrorController
 * @package OTT\Roadmap\Module\Roadmap\Controller
 */
class ErrorController extends AbstractController
{
    public function error404Action()
    {
        $this->preload();
        return $this->render();
    }
}
