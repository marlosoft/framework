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
}
