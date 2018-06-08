<?php

namespace Marlosoft\Framework\Views;

use Marlosoft\Framework\Core\Config;

/**
 * Class TemplateView
 * @package Marlosoft\Framework\Views
 */
class TemplateView extends View
{
    /**
     * @return void
     */
    public function sendResponse()
    {
        $this->setVariables(['contents' => $this->compile()]);
        $this->setPath(Config::get('view.template'));

        echo($this->compile());
    }
}
