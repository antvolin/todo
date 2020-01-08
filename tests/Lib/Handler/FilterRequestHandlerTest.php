<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Handler\FilterRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class FilterRequestHandlerTest extends TestCase
{
    /**
     * @var string
     */
    protected $originalText;

    /**
     * @var Request
     */
    protected $request;

    protected function setUp()
    {
        $this->originalText = '<script>alert("test");</script>';
        $this->request = (new App())->getRequest();
        $this->request->request->set('text', $this->originalText);
    }

    /**
     * @test
     */
    public function shouldBeAddingEscapingSpecialCharacters(): void
    {
        (new FilterRequestHandler())->handle($this->request);

        $this->assertNotEquals($this->originalText, $this->request->request->get('text'));
    }

    /**
     * @test
     *
     * @dataProvider specialChars
     *
     * @param string $char
     */
    public function specialCharactersFromRequestValueShouldBeConvertedToHtmlEntity(string $char): void
    {
        (new FilterRequestHandler())->handle($this->request);

        $this->assertFalse(strpos($char, $this->request->request->get('text')));
    }

    /**
     * @return array
     */
    public function specialChars(): array
    {
        return [
            ['>'],
            ['<'],
            ['"'],
            ['`'],
        ];
    }
}
