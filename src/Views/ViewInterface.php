<?php

namespace Marlosoft\Framework\Views;

use Marlosoft\Framework\Exceptions\ViewNotFoundException;

/**
 * Interface ViewInterface
 * @package Marlosoft\Framework\View
 */
interface ViewInterface
{
    /**
     * ViewInterface constructor.
     * @param string|null $path
     */
    public function __construct($path = null);

    /**
     * @return void
     * @throws ViewNotFoundException
     */
    public function createResponse();
}
