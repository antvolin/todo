<?php

namespace Tests\Lib\Service\RequestHandler;

use Todo\Lib\App;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use PHPUnit\Framework\TestCase;
use Todo\Lib\Service\Paginator\PaginatorServiceInterface;
use Todo\Lib\Service\RequestHandler\PaginatorRequestHandlerService;

class PaginatorRequestHandlerServiceTest extends TestCase
{
    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeCreatedPaginatorAndAddedItToTheRequest(): void
    {
        $app = new App();
        $paginatorFactory = $app->getPaginatorFactory();
        $entityManager = $app->getEntityManager();
        $request = $app->getRequest();
        $handler = new PaginatorRequestHandlerService($paginatorFactory, $entityManager);
        $handler->handle($request);

        $this->assertInstanceOf(PaginatorServiceInterface::class, $request->get('paginator'));
    }
}
