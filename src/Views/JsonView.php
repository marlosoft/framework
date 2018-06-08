<?php

namespace Marlosoft\Framework\Views;

/**
 * Class JsonView
 * @package Marlosoft\Framework\Views
 */
class JsonView extends View
{
    /** @var array $headers */
    protected $headers = [
        'Content-Type' => 'application/json; charset=utf-8',
    ];

    /**
     * @return void
     */
    protected function validate()
    {
    }

    /**
     * @return string
     */
    protected function compile()
    {
        return json_encode($this->variables);
    }

    /**
     * JsonView constructor.
     * @param array $variables
     */
    public function __construct($variables = [])
    {
        parent::__construct(null);
        $this->setVariables($variables);
    }
}
