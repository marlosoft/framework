<?php

namespace Marlosoft\Framework\Components;

use Marlosoft\Framework\Misc\ObjectManager;

/**
 * Class Request
 * @package Marlosoft\Framework\Component
 */
class Request extends Component
{
    /** @var Server $server */
    protected $server;

    /**
     * Request Constructor.
     */
    protected function __construct()
    {
        parent::__construct();
        $this->server = ObjectManager::factory(
            'core.class.server',
            Server::class
        );
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($_REQUEST[$key]);
    }

    /**
     * @return array
     */
    public function all()
    {
        return (array)$_REQUEST;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return mixed
     */
    public function get($key, $data = null)
    {
        return $this->has($key) ? $_REQUEST[$key] : $data;
    }

    /**
     * @param string $key
     * @param mixed $data
     */
    public function set($key, $data = null)
    {
        $_REQUEST[$key] = $data;
    }

    /**
     * @param string $method
     * @return bool
     */
    public function isMethod($method)
    {
        $requestMethod = $this->server->requestMethod();
        return (strtoupper($method) === strtoupper($requestMethod));
    }
}
