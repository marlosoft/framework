<?php

namespace Marlosoft\Framework\Core;

/**
 * Class Config
 * @package Marlosoft\Framework\Core
 */
class Config
{
    /** @var array $settings */
    protected static $settings = [];

    /**
     * @param string $key
     * @param mixed $data
     */
    public static function set($key, $data = null)
    {
        self::$settings[$key] = $data;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return mixed
     */
    public static function get($key, $data = null)
    {
        return isset(self::$settings[$key]) ? self::$settings[$key] : $data;
    }
}
