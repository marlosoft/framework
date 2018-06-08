<?php

namespace Marlosoft\Framework\Exceptions;

/**
 * Class MarlosoftException
 * @package Marlosoft\Framework\Exceptions
 */
class FrameworkException extends \Exception
{
    /** @var int $status */
    protected $code = 500;
}
