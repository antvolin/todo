<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Handler\PaginatorRequestHandler;
use BeeJeeMVC\Lib\Paginator\PaginatorInterface;
use PHPUnit\Framework\TestCase;

class PaginatorRequestHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedPaginatorAndAddedItToTheRequest(): void
    {
        $app = new App();
        $paginatorFactory = $app->getPaginatorFactory();
        $taskManager = $app->getTaskManager();

        $request = $app->getRequest();

        (new PaginatorRequestHandler($paginatorFactory, $taskManager))->handle($request);

        $this->assertInstanceOf(PaginatorInterface::class, $request->get('paginator'));
    }
}
