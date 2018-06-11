<?php

namespace Marlosoft\Framework\Components;

use Marlosoft\Framework\Core\Config;

/**
 * Class Session
 * @package Marlosoft\Framework\Component
 */
class Session extends Component
{
    /** @var bool $hasStarted */
    protected $hasStarted = false;

    /**
     * @param array $options
     */
    public function start($options = [])
    {
        if ($this->hasStarted === false) {
            session_start($options);
            $this->hasStarted = true;
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @return array
     */
    public function all()
    {
        return (array)$_SESSION;
    }

    /**
     * @return void
     */
    public function clear()
    {
        $_SESSION = [];
    }

    /**
     * @param string $key
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function flash($key)
    {
        $data = $this->get($key);
        $this->remove($key);

        return $data;
    }

    /**
     * @param bool $delete
     * @return bool
     */
    public function regenerateId($delete = false)
    {
        return session_regenerate_id($delete);
    }

    /**
     * @return bool
     */
    public function destroy()
    {
        session_unset();
        return session_destroy();
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return mixed
     */
    public function get($key, $data = null)
    {
        return $this->has($key) ? $_SESSION[$key] : $data;
    }

    /**
     * @param string $key
     * @param mixed $data
     */
    public function set($key, $data = null)
    {
        $_SESSION[$key] = $data;
    }
}
