<?php

namespace Tests\Lib\Service\RequestHandler;

use Todo\Lib\App;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use PHPUnit\Framework\TestCase;
use Todo\Lib\Exceptions\PdoConnectionException;
use Todo\Lib\Service\Paginator\PaginatorServiceInterface;
use Todo\Lib\Service\RequestHandler\PaginatorRequestHandlerService;

class PaginatorRequestHandlerServiceTest extends TestCase
{
    /**
     * @test
     *
     * @throws NotAllowedEntityName
     * @throws PdoConnectionException
     */
    public function shouldBeCreatedPaginatorAndAddedItToTheRequest(): void
    {
        $app = new App();
        $paginatorFactory = $app->getPaginatorFactory();
        $entityService = $app->getEntityService();
        $entityService->setRepository($app->getRepository());
        $request = $app->getRequest();
        $handler = new PaginatorRequestHandlerService($paginatorFactory, $entityService);
        $handler->handle($request);

        $this->assertInstanceOf(PaginatorServiceInterface::class, $request->get('paginator'));
    }
}
