<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Controller\AuthController;
use BeeJeeMVC\Controller\TaskController;
use BeeJeeMVC\Lib\Factory\RequestFactory;
use BeeJeeMVC\Lib\Factory\TaskFileRepositoryFactory;
use BeeJeeMVC\Lib\Factory\TaskPdoRepositoryFactory;
use BeeJeeMVC\Lib\Factory\TemplateFactory;
use BeeJeeMVC\Lib\Factory\TokenManagerFactory;
use BeeJeeMVC\Lib\Handler\AccessRequestHandler;
use BeeJeeMVC\Lib\Handler\FilterRequestHandler;
use BeeJeeMVC\Lib\Handler\PagingRequestHandler;
use BeeJeeMVC\Lib\Handler\RoleRequestHandler;
use BeeJeeMVC\Lib\Repository\TaskRepositoryInterface;
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
     * @var TokenManager
     */
    private $tokenManager;

    /**
     * @var Environment
     */
    private $template;

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
	public function process(): void
    {
        $this->request = (new RequestFactory())->create();
        $this->tokenManager = (new TokenManagerFactory())->create($this->request);
        $this->template = (new TemplateFactory())->create();

        $this->handleRequest();

        $urlParts = explode('/', trim($this->request->getPathInfo(), '/'));

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
        $accessRequestHandler = new AccessRequestHandler();
        $roleRequestHandler = new RoleRequestHandler();
        $pagingRequestHandler = new PagingRequestHandler();

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
            $this->request,
            $this->tokenManager->getToken(),
            $this->template
        );
    }

    /**
     * @return TaskController
     */
    private function createTaskController(): TaskController
    {
        return new TaskController(
            $this->request,
            new TaskManager($this->createRepo()),
            $this->tokenManager->getToken(),
            $this->template
        );
    }

    /**
     * @return TaskRepositoryInterface
     */
    private function createRepo(): TaskRepositoryInterface
    {
        $repositoryType = $_ENV['REPOSITORY'];

        if ('sqlite' === $repositoryType) {
            $repository = (new TaskPdoRepositoryFactory())->create();
        } else {
            $repository = (new TaskFileRepositoryFactory())->create();
        }

        return $repository;
    }
}
