<?php

namespace BeeJeeMVC\Lib;

class Template
{
    /**
     * @param string $viewName
     * @param array $args
     *
     * @return string
     */
    public function render(string $viewName, array $args = []): string
    {
        ob_start();

        extract($args, EXTR_OVERWRITE);

        include_once(dirname(__DIR__).'/View/'.$viewName.'.html');

        return ob_get_clean();
    }
}
