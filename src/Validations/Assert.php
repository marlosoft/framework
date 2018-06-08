<?php

namespace Marlosoft\Framework\Validations;

/**
 * Class Assert
 * @package Marlosoft\Framework\Validations
 */
class Assert
{
    /**
     * @param mixed $data
     * @return bool
     */
    public function notEmpty($data)
    {
        return !empty($data);
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function digit($data)
    {
        return ctype_digit($data);
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function alnum($data)
    {
        return ctype_alnum($data);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function alpha($data)
    {
        return ctype_alpha($data);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function email($data)
    {
        return (bool)filter_var($data, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param mixed $data
     * @param mixed $min
     * @param mixed $max
     *
     * @return bool
     */
    public function between($data, $min, $max)
    {
        return ($data >= $min) && ($data <= $max);
    }

    /**
     * @param $data
     * @param int $length
     *
     * @return bool|int
     */
    public function length($data, $length)
    {
        return strlen($data) === $length;
    }

    /**
     * @param $data
     * @param int $length
     *
     * @return bool|int
     */
    public function maxLength($data, $length)
    {
        return strlen($data) <= $length;
    }

    /**
     * @param $data
     * @param int $length
     *
     * @return bool|int
     */
    public function minLength($data, $length)
    {
        return strlen($data) >= $length;
    }
}
