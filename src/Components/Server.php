<?php

namespace Marlosoft\Framework\Components;

use Marlosoft\Framework\Misc\ObjectManager;

/**
 * Class Server
 * @package Marlosoft\Framework\Component
 */
class Server extends Component
{
    /** @var Request $request */
    protected $request;

    /**
     * Server Constructor.
     */
    protected function __construct()
    {
        parent::__construct();
        $this->request = ObjectManager::factory(
            'core.class.request',
            Request::class
        );
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($_SERVER[$key]);
    }

    /**
     * @return array
     */
    public function all()
    {
        return (array)$_SERVER;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return mixed
     */
    public function get($key, $data = null)
    {
        return $this->has($key) ? $_SERVER[$key] : $data;
    }

    /**
     * @param string $key
     * @param mixed $data
     */
    public function set($key, $data = null)
    {
        $_SERVER[$key] = $data;
    }

    /**
     * @return string
     */
    public function pathInfo()
    {
        return (string)$this->get('PATH_INFO');
    }

    /**
     * @return string
     */
    public function requestMethod()
    {
        $method = $this->realRequestMethod();
        if ($method === 'POST') {
            /** @var Request $request */
            $request = ObjectManager::factory(
                'core.class.request',
                Request::class
            );
            if ($request->has('_method')) {
                $method = strtoupper($request->get('_method'));
            } elseif ($this->has('HTTP_X_HTTP_METHOD_OVERRIDE')) {
                $method = $method = strtoupper($this->get('HTTP_X_HTTP_METHOD_OVERRIDE'));
            }
        }

        return $method;
    }

    /**
     * @return string
     */
    public function realRequestMethod()
    {
        return strtoupper($this->get('REQUEST_METHOD', 'GET'));
    }
}
