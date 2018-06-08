<?php

namespace Marlosoft\Framework\Routing;

use Marlosoft\Framework\Core\Config;

/**
 * Class RouteCollection
 * @package Marlosoft\Framework\Routing
 */
class RouteCollection
{
    /** @var Route[] $routes */
    protected $routes;

    /**
     * @param string|Route $route
     * @return string
     */
    protected function createKey($route)
    {
        if ($route instanceof Route) {
            return md5($route->getPath());
        }

        return md5($route);
    }

    /**
     * RouteCollection constructor.
     */
    public function __construct()
    {
        $path = Config::get('route.path');
        if (empty($path) || file_exists($path) === false) {
            return;
        }

        /** @noinspection PhpIncludeInspection */
        $routes = (array)require_once($path);
        if (empty($routes)) {
            return;
        }

        foreach ($routes as $path => $options) {
            $this->add(new Route($path, $options['controller'], $options['methods']));
        }
    }

    /**
     * @param Route $route
     */
    public function add(Route $route)
    {
        $this->routes[$this->createKey($route)] = $route;
    }

    /**
     * @param string $path
     * @return Route|null
     */
    public function find($path)
    {
        $key = $this->createKey($path);
        if (isset($this->routes[$key])) {
            return $this->routes[$key];
        }

        foreach ($this->routes as $route) {
            $pattern = sprintf('/^%s$/', str_replace('/', '\\/', $route->getPath()));
            if (preg_match($pattern, preg_quote($path), $arguments)) {
                if (count($arguments) > 0) {
                    array_shift($arguments);
                }

                $route->setArguments($arguments);
                return $route;
            }
        }

        return null;
    }
}
