<?php

namespace BeeJeeMVC\Lib\Factory;

use BeeJeeMVC\Lib\Service\PathService;
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
