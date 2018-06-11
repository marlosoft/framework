<?php

namespace Marlosoft\Framework\Exceptions;

/**
 * Class RouteNotFoundException
 * @package Marlosoft\Framework\Exceptions
 */
class RouteNotFoundException extends FrameworkException
{
    /** @var int $code */
    protected $code = 404;

    /**
     * RouteNotFoundException constructor.
     * @param string $path
     * @param string $method
     */
    public function __construct($path, $method = 'GET')
    {
        parent::__construct(sprintf('Route "%s %s" not found', $method, $path));
    }
}
