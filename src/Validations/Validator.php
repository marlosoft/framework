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
            foreach ($validation as $method => $message) {
                if (method_exists($this->assert, $method)) {
                    if ($this->assert->{$method}($data) === false) {
                        $this->errors[$key][] = $message;
                    }
                } elseif (method_exists($this->model, $method)) {
                    if ($this->model->{$method}($data) === false) {
                        $this->errors[$key][] = $message;
                    }
                } elseif (function_exists($method)) {
                    if ($method($data) === false) {
                        $this->errors[$key][] = $message;
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
