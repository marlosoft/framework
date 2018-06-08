<?php

namespace Marlosoft\Framework\Views;

use Marlosoft\Framework\Core\Config;
use Marlosoft\Framework\Exceptions\ViewNotFoundException;

/**
 * Class AbstractView
 * @package Marlosoft\Framework\View
 */
class View implements ViewInterface
{
    /** @var string $path */
    protected $path;

    /** @var int $status */
    protected $status = 200;

    /** @var array $variables */
    protected $variables = [];

    /** @var array $headers */
    protected $headers = [];

    /**
     * @return void
     */
    protected function initialize()
    {
        http_response_code($this->status);
        foreach ($this->headers as $k => $v) {
            header(sprintf('%s: %s', $k, $v));
        }
    }

    /**
     * @throws ViewNotFoundException
     */
    protected function validate()
    {
        if (file_exists($this->path) === false) {
            throw new ViewNotFoundException($this->path);
        }
    }

    /**
     * @return string
     */
    protected function compile()
    {
        ob_start('ob_gzhandler');
        extract($this->variables, EXTR_SKIP);

        /** @noinspection PhpIncludeInspection */
        require_once($this->path);
        return ob_get_clean();
    }

    /**
     * @return void
     */
    protected function sendResponse()
    {
        echo($this->compile());
    }

    /**
     * View constructor.
     * @param string $path
     */
    public function __construct($path = null)
    {
        if (empty($path) === false) {
            $this->setPath($path);
        }
    }

    /**
     * @throws ViewNotFoundException
     * @return void
     */
    public function createResponse()
    {
        $this->validate();
        $this->initialize();
        $this->sendResponse();
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = sprintf('%s/%s', Config::get('view.path'), $path);
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $k => $v) {
            $this->headers[$k] = $v;
        }
        return $this;
    }

    /**
     * @param array $variables
     * @return $this
     */
    public function setVariables(array $variables)
    {
        foreach ($variables as $k => $v) {
            $this->variables[$k] = $v;
        }
        return $this;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = (int)$status;
        return $this;
    }
}
