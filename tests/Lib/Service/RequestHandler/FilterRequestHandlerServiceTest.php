<?php

namespace Tests\Lib\Service\RequestHandler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Todo\Lib\App;
use Todo\Lib\Service\RequestHandler\FilterRequestHandlerService;

class FilterRequestHandlerServiceTest extends TestCase
{
    private string $originalText;
    private Request $request;
    private FilterRequestHandlerService $filterRequestHandler;

    protected function setUp()
    {
        $this->originalText = '<script>alert("test");</script>';
        $this->request = (new App())->getRequest();
        $this->request->request->set('text', $this->originalText);
        $this->filterRequestHandler = new FilterRequestHandlerService();
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
     * @param string $specialChar
     */
    public function specialCharactersFromRequestValueShouldBeConvertedToHtmlEntity(string $specialChar): void
    {
        $this->filterRequestHandler->handle($this->request);

        $this->assertFalse(strpos($specialChar, $this->request->request->get('text')));
    }

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
