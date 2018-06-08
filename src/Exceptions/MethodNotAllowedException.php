<?php

namespace Marlosoft\Framework\Exceptions;

/**
 * Class MethodNotAllowedException
 * @package Marlosoft\Framework\Exceptions
 */
class MethodNotAllowedException extends FrameworkException
{
    /** @var int $code */
    protected $code = 405;

    /**
     * RouteNotFoundException constructor.
     * @param string $path
     * @param string $method
     */
    public function __construct($path, $method = 'GET')
    {
        parent::__construct(sprintf('Method "%s" not allowed in route "%s"', $method, $path));
    }
}
