<?php

namespace Marlosoft\Framework\Database;

/**
 * Class PDOStatement
 * @package Marlosoft\Framework\Database
 */
class PDOStatement extends \PDOStatement
{
    /** @var float $timestamp */
    protected $timestamp;

    /**
     * @param null|array $parameters
     * @return bool
     */
    public function execute($parameters = null)
    {
        $timestamp = microtime(true);
        $data = parent::execute($parameters);
        $this->timestamp = (microtime(true) - $timestamp);

        return $data;
    }

    /**
     * @return float
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
