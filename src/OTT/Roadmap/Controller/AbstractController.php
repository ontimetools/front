<?php

namespace OTT\Roadmap\Controller;

use OTT\Processor\Entity\User;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Client as ClientEntity;
use OTT\Roadmap\Module\Roadmap\Model\Service\Application;
use OTT\Roadmap\Server;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class AbstractController
 * @package OTT\Roadmap\Controller
 */
abstract class AbstractController
{
    /** @var SymfonyRequest $request */
    protected $request;
    /** @var array */
    protected $viewParameters = [];
    /** @var User $user */
    protected $user;
    /** @var ClientEntity */
    protected $client;

    /**
     *
     */
    public function __construct()
    {
        $this->setRequest(Server::getService('request'));
        $this->setUser(Application::getInstance()->getCurrentUser()->getResult());
        $this->setClient(Application::getInstance()->getCurrentClient()->getResult());
    }

    /**
     * @Todo : Un peu sale, voir si on peut pas faire mieux
     * @param null $defaultRoute
     */
    final protected function preload($defaultRoute = null)
    {
        $currentRoute = $this->getRequest()->attributes->get('_route');
        $redirectRoute = null;
        if ($currentRoute !== 'error404') {
            if (null === $this->getUser()) {
                if ($currentRoute !== 'login' && $currentRoute !== 'signin') {
                    $redirectRoute = 'login';
                }
            } else {
                if ($currentRoute === 'login' || $currentRoute === 'signin') {
                    $redirectRoute = (null === $defaultRoute) ? 'index' : $defaultRoute;
                }
            }
            if (!Application::getInstance()->getCurrentClient()->isSuccess()) {
                $redirectRoute = 'error404';
            }
        }
        if (null !== $redirectRoute) {
            $this->redirect($this->getUrl($redirectRoute));
        }
    }

    /**
     * Render template from twig engine (with default / specific template)
     * @param null $template
     * @return mixed
     */
    final public function render($template = null)
    {
        if (Server::issetService('twig.currentTmpl')) {
            $template = is_null($template) ? Server::getService('twig.currentTmpl') : $template;
            Server::unsetService('twig.currentTmpl');
        }
        if (Server::issetService('twig.currentCss')) {
            $this->setViewParameters(
                array_merge($this->getViewParameters(), ['currentCss' => Server::getService('twig.currentCss')])
            );
            Server::unsetService('twig.currentCss');
        }
        return Server::getService('twig')->render($template, $this->getViewParameters());
    }

    /**
     * @param $url
     */
    public function redirect($url)
    {
        Server::getInstance()->redirect($url)->send();
    }

    /**
     * @param $route
     * @param array $params
     * @return mixed
     */
    public function getUrl($route, $params = [])
    {
        return Server::getInstance()['url_generator']->generate($route, $params);
    }

    /**
     * @return mixed
     */
    protected function getRouteParams()
    {
        return $this->getRequest()->attributes->get('_route_params');
    }

    /**
     * @param $key
     * @return null
     */
    protected function getRouteParam($key)
    {
        $params = $this->getRouteParams();
        return isset($params[$key]) ? $params[$key] : null;
    }

    /**
     * @return ClientEntity
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param ClientEntity $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return SymfonyRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param SymfonyRequest $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


    /**
     * @return array
     */
    public function getViewParameters()
    {
        if (null === $this->getViewParameter('current_user')) {
            $this->addViewParameters('current_user', $this->getUser());
        }
        if (null === $this->getViewParameter('current_client')) {
            $this->addViewParameters('current_client', $this->getClient());
        }
        if (null === $this->getViewParameter('current_route_family')) {
            $routeFamily = explode('.', $this->getRequest()->attributes->get('_route'));
            $this->addViewParameters('current_route_family', $routeFamily[0]);
        }
        return $this->viewParameters;
    }

    /**
     * @return array
     */
    public function getViewParameter($key)
    {
        return isset($this->viewParameters[$key]) ? $this->viewParameters[$key] : null;
    }

    /**
     * @param array $viewParameters
     */
    public function setViewParameters($viewParameters)
    {
        $this->viewParameters = $viewParameters;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addViewParameters($key, $value)
    {
        $this->viewParameters[$key] = $value;
    }

    /**
     * @param $content
     * @param Response $response
     * @return Response
     */
    final public function encodeResponse($content, $response = null)
    {
        $response = ($response instanceof Response) ? $response : new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($this->formatContent($content));
        return $response;
    }

    /**
     * @param $content
     * @return string
     */
    final public function formatContent($content)
    {
        return json_encode($content);
    }
}
