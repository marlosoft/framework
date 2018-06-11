<?php

namespace Marlosoft\Framework\Components;

use Marlosoft\Framework\Core\Config;

/**
 * Class Logger
 * @package Marlosoft\Framework\Components
 */
class Logger extends Component
{
    const DEBUG = 'debug';
    const INFO = 'info';
    const WARN = 'warn';
    const ERROR = 'error';

    /** @var int[] $levels */
    protected $levels = [
        self::DEBUG => 0,
        self::INFO => 1,
        self::WARN => 2,
        self::ERROR => 3,
    ];

    /** @var int $level */
    protected $level = 0;

    /** @var string $file */
    protected $file;

    /** @var bool $enabled */
    protected $enabled = true;

    /**
     * Logger constructor.
     */
    protected function __construct()
    {
        $this->level = (int)Config::get('log.level', 0);
        $this->file = (string)Config::get('log.file');
        $this->enabled = (bool)Config::get('log.enabled', true);
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $data
     */
    public function log($level, $message, $data = [])
    {
        if ($this->enabled === false) {
            return;
        }

        $lvl = (int)$this->levels[$level];
        $timestamp = date('Y-m-d H:i:s');

        if ($lvl < $this->level) {
            return;
        }

        $msg = sprintf('[%s][%s] %s %s', $timestamp, strtoupper($level), $message, json_encode($data));
        file_put_contents($this->file, $msg . PHP_EOL, LOCK_EX | FILE_APPEND);
    }

    /**
     * @param string $message
     * @param array $data
     */
    public function debug($message, $data = [])
    {
        $this->log(self::DEBUG, $message, $data);
    }

    /**
     * @param string $message
     * @param array $data
     */
    public function info($message, $data = [])
    {
        $this->log(self::INFO, $message, $data);
    }

    /**
     * @param string $message
     * @param array $data
     */
    public function warn($message, $data = [])
    {
        $this->log(self::WARN, $message, $data);
    }

    /**
     * @param string $message
     * @param array $data
     */
    public function error($message, $data = [])
    {
        $this->log(self::ERROR, $message, $data);
    }
}
