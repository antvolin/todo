<?php

namespace Todo\Lib\Factory\Template;

use Todo\Lib\Service\Path\PathService;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TemplateFactory implements TemplateFactoryInterface
{
    /**
     * @return TemplateAdapterInterface
     */
    public function create(): TemplateAdapterInterface
    {
        $loader = new FilesystemLoader(PathService::getPathToTemplates());
        $twigTemplate = new Environment($loader, ['autoescape' => false]);

        return new TwigTemplateAdapter($twigTemplate);
    }
}
