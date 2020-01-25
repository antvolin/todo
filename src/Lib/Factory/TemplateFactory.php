<?php

namespace Todo\Lib\Factory;

use Todo\Lib\Service\Path\PathService;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TemplateFactory
{
    /**
     * @return Environment
     */
    public function create(): Environment
    {
        $loader = new FilesystemLoader(PathService::getPathToTemplates());

        return new Environment($loader, ['autoescape' => false]);
    }
}
