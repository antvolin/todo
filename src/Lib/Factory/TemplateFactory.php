<?php

namespace BeeJeeMVC\Lib\Factory;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TemplateFactory
{
    /**
     * @return Environment
     */
    public function create(): Environment
    {
        $loader = new FilesystemLoader(dirname(__DIR__).'/../../'.'templates');

        return new Environment($loader, ['autoescape' => false]);
    }
}
