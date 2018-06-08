<?php

namespace Marlosoft\Framework\Misc;

/**
 * Class Singleton
 * @package Marlosoft\Framework\Misc
 */
abstract class Singleton
{
    /**
     * Singleton constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        static $instance;
        if (empty($instance)) {
            $instance = new static();
        }

        return $instance;
    }
}
