<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Controller\AuthController;
use BeeJeeMVC\Controller\TaskController;
use BeeJeeMVC\Lib\Handler\AccessRequestHandler;
use BeeJeeMVC\Lib\Handler\FilterRequestHandler;
use BeeJeeMVC\Lib\Handler\PaginatorRequestHandler;
use BeeJeeMVC\Lib\Handler\RoleRequestHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Kernel
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $token;

    /**
     * @var Environment
     */
    private $template;

    /**
     * @var TaskManager
     */
    private $taskManager;

    /**
     * @var App
     */
    private $app;

    /**
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
	public function process(): void
    {
        $this->request = $this->app->getRequest();
        $this->token = $this->app->getToken();
        $this->template = $this->app->getTemplate();
        $this->taskManager = $this->app->getTaskManager();

        $this->handleRequest();

        $urlParts = PathSeparator::separate($this->request->getPathInfo());

        if ('auth' === strtolower(array_shift($urlParts))) {
            $controller = $this->createAuthController();
        } else {
            $controller = $this->createTaskController();
        }

        if (method_exists($controller, $action = array_shift($urlParts))) {
            /** @var Response $response */
            $response = $controller->$action(...$urlParts);
        } else {
            $response = $controller->list();
        }

        $response->send();
	}

    private function handleRequest(): void
    {
        $filterRequestHandler = new FilterRequestHandler();
        $accessRequestHandler = new AccessRequestHandler(
            $this->app->getTokenManagerFactory()
        );
        $roleRequestHandler = new RoleRequestHandler();
        $pagingRequestHandler = new PaginatorRequestHandler(
            $this->app->getPaginatorFactory(),
            $this->taskManager
        );

        $filterRequestHandler
            ->setNextHandler($accessRequestHandler)
            ->setNextHandler($roleRequestHandler)
            ->setNextHandler($pagingRequestHandler);

        $filterRequestHandler->handle($this->request);
    }

    /**
     * @return AuthController
     */
	private function createAuthController(): AuthController
    {
        return new AuthController(
            $this->token,
            $this->request,
            $this->template
        );
    }

    /**
     * @return TaskController
     */
    private function createTaskController(): TaskController
    {
        return new TaskController(
            $this->token,
            $this->request,
            $this->taskManager,
            $this->template
        );
    }
}
