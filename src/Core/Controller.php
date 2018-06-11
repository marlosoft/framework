<?php

namespace Marlosoft\Framework\Core;

use Marlosoft\Framework\Components\Logger;
use Marlosoft\Framework\Components\Request;
use Marlosoft\Framework\Components\Server;
use Marlosoft\Framework\Components\Session;
use Marlosoft\Framework\Misc\ObjectManager;
use Marlosoft\Framework\Views\JsonView;
use Marlosoft\Framework\Views\RedirectView;
use Marlosoft\Framework\Views\TemplateView;
use Marlosoft\Framework\Views\View;

/**
 * Class Controller
 * @package Marlosoft\Framework\Core
 */
class Controller
{
    /** @var Logger $logger */
    protected $logger;

    /** @var Server $server */
    protected $server;

    /** @var Session $session */
    protected $session;

    /** @var Request $request */
    protected $request;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->logger = ObjectManager::factory('core.class.logger', Logger::class);
        $this->server = ObjectManager::factory('core.class.server', Server::class);
        $this->session = ObjectManager::factory('core.class.session', Session::class);
        $this->request = ObjectManager::factory('core.class.request', Request::class);
    }

    /**
     * @param array $variables
     * @param int $status
     * @param array $headers
     * @return JsonView
     */
    public function json($variables = [], $status = 200, $headers = [])
    {
        $view = new JsonView($variables);
        $view->setHeaders($headers);
        $view->setStatus($status);

        return $view;
    }

    /**
     * @param string $render
     * @param array $variables
     * @param int $status
     * @param array $headers
     * @return TemplateView|View
     */
    public function render($render, $variables = [], $status = 200, $headers = [])
    {
        $template = Config::get('view.template');
        $view = empty($template) ? new View($render) : new TemplateView($render);
        $view->setVariables($variables);
        $view->setHeaders($headers);
        $view->setStatus($status);

        return $view;
    }

    /**
     * @param string $path
     * @param array $parameters
     * @return RedirectView
     */
    public function redirect($path, $parameters = [])
    {
        $view = new RedirectView($path);
        $view->setParameters($parameters);

        return $view;
    }
}
