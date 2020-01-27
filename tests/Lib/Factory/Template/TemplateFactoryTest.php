<?php

namespace Tests\Lib\Factory\Template;

use PHPUnit\Framework\TestCase;
use Todo\Lib\Factory\Template\TemplateFactory;

class TemplateFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedTemplate(): void
    {
        $factory = new TemplateFactory();
        $template = $factory->create();

        $this->assertNotEmpty($template->render('list.html.twig'));
    }
}
