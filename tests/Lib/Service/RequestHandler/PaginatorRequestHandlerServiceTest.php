<?php

namespace Tests\Lib\Service\RequestHandler;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotCreateDirectoryException;
use Todo\Lib\Service\Paginator\PaginatorServiceInterface;
use Todo\Lib\Service\RequestHandler\PaginatorRequestHandlerService;

class PaginatorRequestHandlerServiceTest extends TestCase
{
    /**
     * @test
     *
     * @throws CannotCreateDirectoryException
     */
    public function shouldBeCreatedPaginatorAndAddedItToTheRequest(): void
    {
        $app = new App();
        $paginatorFactory = $app->createPaginatorFactory();
        $entityService = $app->createEntityService();
        $entityService->setRepository($app->createRepository());
        $request = $app->getRequest();
        $handler = new PaginatorRequestHandlerService($paginatorFactory, $entityService);
        $handler->handle($request);

        $this->assertInstanceOf(PaginatorServiceInterface::class, $request->get('paginator'));
    }
}
