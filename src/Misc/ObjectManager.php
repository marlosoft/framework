<?php

namespace Marlosoft\Framework\Misc;

use Marlosoft\Framework\Components\Component;
use Marlosoft\Framework\Core\Config;

/**
 * Class ObjectManager
 * @package Marlosoft\Framework\Misc
 */
class ObjectManager
{
    protected static function call($class)
    {
        return call_user_func(sprintf('%s::getInstance', $class));
    }

    /**
     * @param string $key
     * @param string $default
     *
     * @return Component
     */
    public static function factory($key, $default)
    {
        $class = Config::get($key, $default);
        return self::call($class);
    }

    /**
     * @param string $class
     *
     * @return mixed
     */
    public static function create($class)
    {
        return self::call($class);
    }
}
