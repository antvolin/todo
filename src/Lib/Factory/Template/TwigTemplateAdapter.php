<?php

namespace Todo\Lib\Factory\Template;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TwigTemplateAdapter implements TemplateAdapterInterface
{
    private Environment $template;

    public function __construct(Environment $template)
    {
        $this->template = $template;
    }

    /**
     * @param string $name
     * @param array $context
     *
     * @return string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $name, array $context = []): string
    {
        return $this->template->render($name, $context);
    }
}
