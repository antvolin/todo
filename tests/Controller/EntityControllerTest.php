<?php

namespace Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Todo\Controller\EntityController;
use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotCreateDirectoryException;
use Todo\Lib\Exceptions\PdoConnectionException;
use Todo\Lib\Exceptions\RedisConnectionException;
use Todo\Lib\Factory\Paginator\PaginatorFactoryInterface;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Service\RequestHandler\PaginatorRequestHandlerService;
use Todo\Lib\Traits\TestValueGenerator;

class EntityControllerTest extends TestCase
{
    use TestValueGenerator;

    private PaginatorFactoryInterface $paginatorFactory;
    private EntityController $controller;
    private EntityServiceInterface $entityService;
    private Request $request;

    /**
     * @throws CannotCreateDirectoryException
     * @throws PdoConnectionException
     * @throws RedisConnectionException
     */
    protected function setUp()
    {
        $app = new App();
        $this->paginatorFactory = $app->createPaginatorFactory();
        $this->request = $app->getRequest();
        $this->entityService = $app->createEntityService();
        $this->entityService->setRepository($app->createRepository());
        $this->controller = new EntityController($this->request, $this->entityService, $app->createTemplate());
    }

    /**
     * @test
     */
    public function shouldBeGettingParamsForEntityListPage(): void
    {
        $this->markTestIncomplete();

        $handler = new PaginatorRequestHandlerService(
            $this->paginatorFactory,
            $this->entityService
        );
        $handler->handle($this->request);

        $response = $this->controller->list();

        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertIsBool($content['isAdmin']);
        $this->assertIsBool($content['isCreated']);
        $this->assertIsInt($content['page']);
        $this->assertGreaterThan(0, $content['page']);
        $this->assertIsString($content['order']);
        $this->assertNotEmpty($content['order']);

        $entities = $content['entities'];

        $this->assertCount(3, $entities);

        foreach ($entities as $entity) {
            $this->assertIsInt($entity['id']);
            $this->assertGreaterThan(0, $entity['id']);
            $this->assertIsString($entity['userName']);
            $this->assertNotEmpty($entity['userName']);
            $this->assertIsString($entity['email']);
            $this->assertNotEmpty($entity['email']);
            $this->assertIsString($entity['text']);
            $this->assertNotEmpty($entity['text']);
            $this->assertArrayHasKey('status', $entity);
        }

        $this->assertGreaterThan(0, $content['page']);
        $this->assertIsString($content['pagination']);
        $this->assertNotEmpty($content['pagination']);

//        $this->assertContains('<title>Tasks book</title>', $response->getContent());
    }

    /**
     * @test
     */
    public function shouldBeGettingCreatePage(): void
    {
        $handler = new PaginatorRequestHandlerService(
            $this->paginatorFactory,
            $this->entityService
        );
        $handler->handle($this->request);

        $response = $this->controller->create();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertContains('<title>Task create</title>', $response->getContent());
    }

    /**
     * @test
     */
    public function shouldBeEntityCreatable(): void
    {
        $handler = new PaginatorRequestHandlerService(
            $this->paginatorFactory,
            $this->entityService
        );
        $handler->handle($this->request);
        $this->request->setMethod('POST');
        $this->request->request->set('user_name', $this->generateUserName(__METHOD__, __CLASS__));
        $this->request->request->set('email', $this->generateEmail());
        $this->request->request->set('text', $this->generateText(__METHOD__, __CLASS__));

        $response = $this->controller->create();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/entity/list', $response->getTargetUrl());
    }

    /**
     * @test
     */
    public function shouldBeEntityEditable(): void
    {
        $handler = new PaginatorRequestHandlerService(
            $this->paginatorFactory,
            $this->entityService
        );
        $handler->handle($this->request);
        $this->request->setMethod('POST');

        $this->request->request->set('user_name', $this->generateUserName(__METHOD__, __CLASS__));
        $this->request->request->set('email', $this->generateEmail());
        $this->request->request->set('text', $this->generateText(__METHOD__, __CLASS__));

        $this->controller->create();
        $this->request->request->set('id', $this->request->request->get('entity_id'));
        $response = $this->controller->edit($this->request->request->get('entity_id'));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/entity/list', $response->getTargetUrl());
    }

    /**
     * @test
     */
    public function shouldBeEntityDone(): void
    {
        $handler = new PaginatorRequestHandlerService(
            $this->paginatorFactory,
            $this->entityService
        );
        $handler->handle($this->request);
        $this->request->setMethod('POST');

        $this->request->request->set('user_name', $this->generateUserName(__METHOD__, __CLASS__));
        $this->request->request->set('email', $this->generateEmail());
        $this->request->request->set('text', $this->generateText(__METHOD__, __CLASS__));

        $this->controller->create();
        $response = $this->controller->done($this->request->request->get('entity_id'));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/entity/list', $response->getTargetUrl());
    }
}
