<?php

namespace Marlosoft\Framework\Routing;

use Marlosoft\Framework\Components\Logger;
use Marlosoft\Framework\Components\Server;
use Marlosoft\Framework\Exceptions\MethodNotAllowedException;
use Marlosoft\Framework\Exceptions\RouteNotFoundException;

/**
 * Class Router
 * @package Marlosoft\Framework\Routing
 */
class Router
{
    /** @var RouteCollection $collection */
    protected $collection;

    /** @var Server $server */
    protected $server;

    /** @var Logger $logger */
    protected $logger;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->collection = new RouteCollection();
        $this->server = Server::getInstance();
        $this->logger = Logger::getInstance();
    }

    /**
     * @return Route
     * @throws MethodNotAllowedException
     * @throws RouteNotFoundException
     */
    public function parse()
    {
        $path = $this->server->pathInfo();
        $method = $this->server->requestMethod();
        $route = $this->collection->find($path);

        if (empty($route)) {
            throw new RouteNotFoundException($path, $method);
        }

        if (in_array($method, $route->getMethods()) === false) {
            throw new MethodNotAllowedException($path, $method);
        }

        $this->logger->debug('Route matched', $route->toArray());
        return $route;
    }
}
