<?php

namespace Marlosoft\Framework\Exceptions;

/**
 * Class ViewNotFoundException
 * @package Marlosoft\Framework\Exceptions
 */
class ViewNotFoundException extends FrameworkException
{
    /** @var int $code */
    protected $code = 404;

    /**
     * ViewNotFoundException constructor.
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct(sprintf('View file "%s" not found', $path));
    }
}
