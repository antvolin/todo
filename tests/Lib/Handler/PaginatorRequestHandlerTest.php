<?php

namespace Todo\Tests\Lib\Handler;

use Todo\Lib\App;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\RequestHandler\PaginatorRequestHandler;
use Todo\Lib\Paginator\PaginatorInterface;
use PHPUnit\Framework\TestCase;

class PaginatorRequestHandlerTest extends TestCase
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
        $handler = new PaginatorRequestHandler($paginatorFactory, $entityManager);
        $handler->handle($request);

        $this->assertInstanceOf(PaginatorInterface::class, $request->get('paginator'));
    }
}
