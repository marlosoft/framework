<?php

namespace Marlosoft\Framework\Exceptions;

/**
 * Class PDOException
 * @package Marlosoft\Framework\Exceptions
 */
class PDOException extends \PDOException
{
    /** @var int $code */
    protected $code = 500;
}
