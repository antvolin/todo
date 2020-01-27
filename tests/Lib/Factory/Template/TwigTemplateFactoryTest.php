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

        $this->assertNotEmpty($template->render('list.html.twig'));
    }
}
