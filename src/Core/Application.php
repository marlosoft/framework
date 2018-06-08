<?php

namespace Marlosoft\Framework\Core;

use Marlosoft\Framework\Exceptions\ViewNotFoundException;
use Marlosoft\Framework\Misc\Singleton;
use Marlosoft\Framework\Routing\Route;
use Marlosoft\Framework\Routing\Router;
use Marlosoft\Framework\Views\ViewInterface;

/**
 * Class Application
 * @package Marlosoft\Framework\Core
 */
class Application extends Singleton
{
    /** @var Router $router */
    protected $router;

    /** @var \Closure $errorHandler */
    protected $errorHandler;

    /**
     * Application constructor.
     */
    protected function __construct()
    {
        $routerClass = Config::get('core.class.router', Router::class);
        $this->router = new $routerClass();

        $this->errorHandler = function(\Exception $exception) {
            http_response_code($exception->getCode());
            echo(nl2br($exception->__toString()));
        };
    }

    /**
     * @param Route $route
     * @return array
     */
    protected function extract(Route $route)
    {
        list($controllerName, $methodName) = $route->extract();
        $controller = new $controllerName();

        return [$controller, $methodName];
    }

    /**
     * @param Route $route
     * @param Controller $controller
     * @param string $type
     *
     * @throws ViewNotFoundException
     */
    protected function applyMiddleware(Route $route, Controller $controller, $type = 'before')
    {
        $middlewares = $route->getMiddlewares($type);
        if (empty($middlewares)) {
            return;
        }

        foreach ($middlewares as $middleware) {
            $view = null;
            if (method_exists($controller, $middleware)) {
                $view = $controller->{$middleware}();
            } elseif (is_callable($middleware)) {
                $view = call_user_func($middleware);
            }

            if ($view instanceof ViewInterface) {
                $view->createResponse();
                return;
            }
        }
    }

    /**
     * Dispatch application
     */
    public function dispatch()
    {
        try {
            $route = $this->router->parse();
            list($controller, $method) = $this->extract($route);

            $this->applyMiddleware($route, $controller, 'before');
            $view = call_user_func_array(
                [$controller, $method],
                $route->getArguments()
            );
            $this->applyMiddleware($route, $controller, 'after');
            $view->createResponse();
        } catch (\Exception $exception) {
            $errorHandler = Config::get('app.errorHandler', $this->errorHandler);
            $errorHandler($exception);
        }
    }
}
