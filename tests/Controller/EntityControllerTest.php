<?php

namespace Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Todo\Controller\EntityController;
use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\PdoConnectionException;
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
     * @throws PdoConnectionException
     */
    protected function setUp()
    {
        $app = new App();
        $this->paginatorFactory = $app->getPaginatorFactory();
        $this->request = $app->getRequest();
        $this->entityService = $app->getEntityService();
        $this->entityService->setRepository($app->getRepository());
        $this->controller = new EntityController($this->request, $this->entityService, $app->getTemplate());
    }

    /**
     * @test
     */
    public function shouldBeGettingParamsForEntityListPage(): void
    {
        $this->markTestIncomplete();

        $handler = new PaginatorRequestHandlerService(
            null,
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
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function shouldBeGettingCreatePage(): void
    {
        $handler = new PaginatorRequestHandlerService(
            null,
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
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function shouldBeEntityCreatable(): void
    {
        $handler = new PaginatorRequestHandlerService(
            null,
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
     *
     * @throws CannotBeEmptyException
     * @throws EntityNotFoundException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function shouldBeEntityEditable(): void
    {
        $handler = new PaginatorRequestHandlerService(
            null,
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
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function shouldBeEntityDone(): void
    {
        $handler = new PaginatorRequestHandlerService(
            null,
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
