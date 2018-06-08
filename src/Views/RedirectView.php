<?php

namespace Marlosoft\Framework\Views;

/**
 * Class RedirectView
 * @package Marlosoft\Framework\Views
 */
class RedirectView implements ViewInterface
{
    /** @var string $path */
    protected $path;

    /** @var array $parameters */
    protected $parameters = [];

    /**
     * RedirectView constructor.
     * @param string $path
     */
    public function __construct($path = null)
    {
        $this->setPath($path);
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters($parameters = [])
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return void
     */
    public function createResponse()
    {
        $query = null;
        if (empty($this->parameters) === false) {
            $query = sprintf('?%s', http_build_query($this->parameters));
        }

        header(sprintf('Location: %s%s', $this->path, $query));
        exit(0);
    }
}
