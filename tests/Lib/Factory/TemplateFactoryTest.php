<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\Factory\TemplateFactory;
use PHPUnit\Framework\TestCase;

class TemplateFactoryTest extends TestCase
{
    /**
     * @test
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function shouldBeCreatedTemplate(): void
    {
        $template = (new TemplateFactory())->create();
        $this->assertNotEmpty($template->render('list.html.twig'));
    }
}
