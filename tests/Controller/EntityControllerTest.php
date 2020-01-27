<?php

namespace Tests\Controller;

use Todo\Controller\EntityController;
use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\CannotDoneEntityException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Exceptions\EntityNotFoundException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\PdoConnectionException;
use Todo\Lib\Exceptions\PdoErrorsException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Service\RequestHandler\PaginatorRequestHandlerService;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EntityControllerTest extends TestCase
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var EntityController
     */
    protected $controller;

    /**
     * @var EntityServiceInterface
     */
    protected $entityService;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @throws NotAllowedEntityName
     * @throws PdoConnectionException
     */
    protected function setUp()
    {
        $this->app = new App();
        $this->request = $this->app->getRequest();
        $this->entityService = $this->app->getEntityService();
        $this->controller = new EntityController($this->request, $this->entityService, $this->app->getRepository(), $this->app->getTemplate());
    }

    /**
     * @test
     *
     * @throws LoaderError
     * @throws NotAllowedEntityName
     * @throws PdoConnectionException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function shouldBeGettingParamsForEntityListPage(): void
    {
        $this->markTestIncomplete();

        $handler = new PaginatorRequestHandlerService(
            $this->app->getPaginatorFactory(),
            $this->entityService,
            $this->app->getRepository()
        );
        $handler->handle($this->request);

        $response = $this->controller->list();

        $content = json_decode($response->getContent(), true);

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
     * @throws LoaderError
     * @throws NotAllowedEntityName
     * @throws NotValidEmailException
     * @throws PdoConnectionException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function shouldBeGettingCreatePage(): void
    {
        $handler = new PaginatorRequestHandlerService(
            $this->app->getPaginatorFactory(),
            $this->entityService,
            $this->app->getRepository()
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
     * @throws LoaderError
     * @throws NotAllowedEntityName
     * @throws NotValidEmailException
     * @throws PdoConnectionException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function shouldBeEntityCreatable(): void
    {
        $handler = new PaginatorRequestHandlerService(
            $this->app->getPaginatorFactory(),
            $this->entityService,
            $this->app->getRepository()
        );
        $handler->handle($this->request);
        $this->request->setMethod('POST');
        $this->request->request->set('user_name', uniqid('user_name'.__METHOD__.__CLASS__, true));
        $this->request->request->set('email', 'test@test.test');
        $this->request->request->set('text', uniqid('text'.__METHOD__.__CLASS__, true));

        $response = $this->controller->create();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/entity/list', $response->getTargetUrl());
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws CannotDoneEntityException
     * @throws CannotEditEntityException
     * @throws EntityNotFoundException
     * @throws ForbiddenStatusException
     * @throws NotAllowedEntityName
     * @throws NotValidEmailException
     * @throws PdoConnectionException
     * @throws PdoErrorsException
     */
    public function shouldBeEntityEditable(): void
    {
        $handler = new PaginatorRequestHandlerService(
            $this->app->getPaginatorFactory(),
            $this->entityService,
            $this->app->getRepository()
        );
        $handler->handle($this->request);
        $this->request->setMethod('POST');

        $this->request->request->set('user_name', uniqid('user_name'.__METHOD__.__CLASS__, true));
        $this->request->request->set('email', 'test@test.test');
        $this->request->request->set('text', uniqid('text'.__METHOD__.__CLASS__, true));

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
     * @throws LoaderError
     * @throws NotAllowedEntityName
     * @throws NotValidEmailException
     * @throws PdoConnectionException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function shouldBeEntityDone(): void
    {
        $handler = new PaginatorRequestHandlerService(
            $this->app->getPaginatorFactory(),
            $this->entityService,
            $this->app->getRepository()
        );
        $handler->handle($this->request);
        $this->request->setMethod('POST');

        $this->request->request->set('user_name', uniqid('user_name'.__METHOD__.__CLASS__, true));
        $this->request->request->set('email', 'test@test.test');
        $this->request->request->set('text', uniqid('text'.__METHOD__.__CLASS__, true));

        $this->controller->create();
        $response = $this->controller->done($this->request->request->get('entity_id'));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/entity/list', $response->getTargetUrl());
    }
}
