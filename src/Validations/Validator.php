<?php

namespace Marlosoft\Framework\Validations;

use Marlosoft\Framework\Core\Config;
use Marlosoft\Framework\Core\Model;

/**
 * Class Validator
 * @package Marlosoft\Framework\Validations
 */
class Validator
{
    /** @var Model */
    protected $model;

    /** @var Assert $assert */
    protected $assert;

    /** @var array $constraints */
    protected $constraints = [];

    /** @var array $errors */
    protected $errors = [];

    /**
     * Validator constructor.
     * @param Model $model
     * @param array $constraints
     */
    public function __construct(Model $model, array $constraints)
    {
        $this->model = $model;
        $this->constraints = $constraints;

        $assertClass = Config::get('core.class.assert', Assert::class);
        $this->assert = new $assertClass();
    }

    /**
     * @return void
     */
    public function validate()
    {
        foreach ($this->constraints as $key => $validation) {
            if (property_exists($this->model, $key) === false) {
                continue;
            }

            $data = $this->model->{$key};
            foreach ($validation as $k => $v) {
                if (is_array($v)) {
                    $message = $v['message'];
                    $method = $assert = $v['assert'];

                    if (is_array($assert)) {
                        $method = array_shift($assert);
                        array_unshift($assert, $data);
                    } else {
                        $assert = [$data];
                    }
                } else {
                    $method = $k;
                    $message = $v;
                    $assert = [$data];
                }

                if (function_exists($method)) {
                    if (call_user_func_array($method, $assert) === false) {
                        $this->errors[$key][] = $message;
                        continue 2;
                    }
                } elseif (method_exists($this->model, $method)) {
                    if (call_user_func_array([$this->model, $method], $assert) === false) {
                        $this->errors[$key][] = $message;
                        continue 2;
                    }
                } elseif (method_exists($this->assert, $method)) {
                    if (call_user_func_array([$this->assert, $method], $assert) === false) {
                        $this->errors[$key][] = $message;
                        continue 2;
                    }
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
