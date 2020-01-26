<?php

namespace Tests\Lib\Service\RequestHandler;

use Todo\Lib\App;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use PHPUnit\Framework\TestCase;
use Todo\Lib\Exceptions\PdoConnectionException as PdoConnectionExceptionAlias;
use Todo\Lib\Service\Paginator\PaginatorServiceInterface;
use Todo\Lib\Service\RequestHandler\PaginatorRequestHandlerService;

class PaginatorRequestHandlerServiceTest extends TestCase
{
    /**
     * @test
     *
     * @throws NotAllowedEntityName
     * @throws PdoConnectionExceptionAlias
     */
    public function shouldBeCreatedPaginatorAndAddedItToTheRequest(): void
    {
        $app = new App();
        $paginatorFactory = $app->getPaginatorFactory();
        $entityService = $app->getEntityService();
        $request = $app->getRequest();
        $handler = new PaginatorRequestHandlerService($paginatorFactory, $entityService, $app->getRepository());
        $handler->handle($request);

        $this->assertInstanceOf(PaginatorServiceInterface::class, $request->get('paginator'));
    }
}
