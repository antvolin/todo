<?php

namespace BeeJeeMVC\Tests\Controller;

use BeeJeeMVC\Controller\EntityController;
use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\CannotEditEntityException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Exceptions\NotFoundException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Exceptions\PdoErrorsException;
use BeeJeeMVC\Lib\Service\EntityServiceInterface;
use BeeJeeMVC\Lib\RequestHandler\PaginatorRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    protected $entityManager;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @throws NotAllowedEntityName
     */
    protected function setUp()
    {
        $this->app = new App();
        $this->request = $this->app->getRequest();
        $this->entityManager = $this->app->getEntityManager();
        $this->controller = new EntityController($this->request, $this->entityManager, $this->app->getTemplate());
    }

    /**
     * @test
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function shouldBeGettingEntitiesList(): void
    {
        $handler = new PaginatorRequestHandler(
            $this->app->getPaginatorFactory(),
            $this->entityManager
        );
        $handler->handle($this->request);

        $response = $this->controller->list();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertContains('<title>Tasks book</title>', $response->getContent());
    }

    /**
     * @test
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function shouldBeGettingCreatePage(): void
    {
        $handler = new PaginatorRequestHandler(
            $this->app->getPaginatorFactory(),
            $this->entityManager
        );
        $handler->handle($this->request);

        $response = $this->controller->create();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertContains('<title>Task create</title>', $response->getContent());
    }

    /**
     * @test
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    public function shouldBeEntityCreatable(): void
    {
        $handler = new PaginatorRequestHandler($this->app->getPaginatorFactory(), $this->entityManager);
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
     * @throws ForbiddenStatusException
     * @throws LoaderError
     * @throws NotValidEmailException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws CannotEditEntityException
     * @throws NotFoundException
     * @throws PdoErrorsException
     */
    public function shouldBeEntityEditable(): void
    {
        $handler = new PaginatorRequestHandler($this->app->getPaginatorFactory(), $this->entityManager);
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
     * @throws NotValidEmailException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function shouldBeEntityDone(): void
    {
        $handler = new PaginatorRequestHandler($this->app->getPaginatorFactory(), $this->entityManager);
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
