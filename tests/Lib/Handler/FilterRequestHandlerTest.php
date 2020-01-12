<?php

namespace BeeJeeMVC\Tests\Lib\Handler;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\RequestHandler\FilterRequestHandler;
use BeeJeeMVC\Lib\RequestHandler\RequestHandler;
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

    /**
     * @var RequestHandler
     */
    protected $filterRequestHandler;

    protected function setUp()
    {
        $this->originalText = '<script>alert("test");</script>';
        $this->request = (new App())->getRequest();
        $this->request->request->set('text', $this->originalText);
        $this->filterRequestHandler = new FilterRequestHandler();
    }

    /**
     * @test
     */
    public function shouldBeAddingEscapingSpecialCharacters(): void
    {
        $this->filterRequestHandler->handle($this->request);

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
        $this->filterRequestHandler->handle($this->request);

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
