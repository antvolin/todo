<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Controller\AuthController;
use BeeJeeMVC\Controller\TaskController;
use BeeJeeMVC\Lib\Factory\TaskFileRepositoryFactory;
use BeeJeeMVC\Lib\Factory\TaskPdoRepositoryFactory;
use BeeJeeMVC\Lib\Handler\AccessRequestHandler;
use BeeJeeMVC\Lib\Handler\FilterRequestHandler;
use BeeJeeMVC\Lib\Handler\PagingRequestHandler;
use BeeJeeMVC\Lib\Handler\RoleRequestHandler;
use BeeJeeMVC\Lib\Repository\TaskRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
	public function process(): void
    {
        $this->createRequest();
        $this->initSession();
        $this->createTokenManager();
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

	private function createRequest(): void
    {
        $this->request = Request::createFromGlobals();
    }

    private function initSession(): void
    {
        $session = new Session();
        $session->start();

        $this->request->setSession($session);
    }

	private function createTokenManager(): void
    {
        $this->tokenManager = new TokenManager();

        if (!$secret = $this->request->getSession()->get('secret')) {
            $secret = (new SecretGenerator())->generateSecret();

            $this->request->getSession()->set('secret', $secret);
        }

        $this->tokenManager->generateToken($secret, $_ENV['TOKEN_SALT']);
    }

    private function handleRequest(): void
    {
        $filterRequestHandler = new FilterRequestHandler();
        $accessRequestHandler = new AccessRequestHandler($this->tokenManager);
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
            $this->createTemplate()
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
            $this->createTemplate()
        );
    }

    /**
     * @return Environment
     */
	private function createTemplate(): Environment
    {
        $loader = new FilesystemLoader(dirname(__DIR__).'/../'.'templates');

        return new Environment($loader, ['autoescape' => false]);
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
