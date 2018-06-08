<?php

namespace Marlosoft\Framework\Routing;

/**
 * Class Route
 * @package Marlosoft\Framework\Routing
 */
class Route
{
    /** @var array $methods */
    protected $methods = ['GET'];

    /** @var string $path */
    protected $path;

    /** @var string $controller */
    protected $controller;

    /** @var array $arguments */
    protected $arguments = [];

    /** @var array $middlewares */
    protected $middlewares = [];

    /**
     * Route constructor.
     * @param string $path
     * @param string $controller
     * @param array $methods
     * @param array $middlewares
     */
    public function __construct($path, $controller, $methods = ['GET'], $middlewares = [])
    {
        $this->path = (string)$path;
        $this->controller = (string)$controller;
        $this->methods = (array)$methods;
        $this->middlewares = (array)$middlewares;
    }

    /**
     * @param array $methods
     * @return $this
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @param array $middlewares
     * @return $this
     */
    public function setMiddlewares(array $middlewares)
    {
        $this->middlewares = $middlewares;
        return $this;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function getMiddlewares($type = 'before')
    {
        if (isset($this->middlewares[$type])) {
            return $this->middlewares[$type];
        }

        return [];
    }

    /**
     * @return array
     */
    public function extract()
    {
        return explode('::', $this->controller, 2);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
