<?php

namespace BeeJeeMVC\Lib\Factory;

use BeeJeeMVC\Lib\Manager\PathManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TemplateFactory
{
    /**
     * @return Environment
     */
    public function create(): Environment
    {
        $loader = new FilesystemLoader(PathManager::getPathToTemplates());

        return new Environment($loader, ['autoescape' => false]);
    }
}
