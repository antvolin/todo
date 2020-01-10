<?php

namespace BeeJeeMVC\Tests\Lib\Factory;

use BeeJeeMVC\Lib\Factory\TemplateFactory;
use PHPUnit\Framework\TestCase;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TemplateFactoryTest extends TestCase
{
    /**
     * @test
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function shouldBeCreatedTemplate(): void
    {
        $template = (new TemplateFactory())->create();
        $this->assertNotEmpty($template->render('list.html.twig'));
    }
}
