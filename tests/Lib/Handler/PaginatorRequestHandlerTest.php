<?php

namespace BeeJeeMVC\Tests\Lib\Handler;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Handler\PaginatorRequestHandler;
use BeeJeeMVC\Lib\Paginator\PaginatorInterface;
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

        (new PaginatorRequestHandler($paginatorFactory, $entityManager))->handle($request);

        $this->assertInstanceOf(PaginatorInterface::class, $request->get('paginator'));
    }
}
