<?php

namespace BeeJeeMVC\Lib;

class Template
{
    /**
     * @param string $viewName
     *
     * @return false|string
     */
    public function render(string $viewName)
    {
        ob_start();

        include_once(dirname(__DIR__).'/View/'.$viewName.'.html');

        return ob_get_clean();
    }
}
