<?php

namespace Tests\Lib\Factory\Template;

use PHPUnit\Framework\TestCase;
use Todo\Lib\Factory\Template\TwigTemplateFactory;

class TwigTemplateFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedTwigTemplate(): void
    {
        $factory = new TwigTemplateFactory();
        $template = $factory->create();
        $result = $template->render('list.html.twig');

        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }
}
