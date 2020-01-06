<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Controller\AuthController;
use BeeJeeMVC\Controller\TaskController;
use BeeJeeMVC\Lib\Handler\AccessRequestHandler;
use BeeJeeMVC\Lib\Handler\FilterRequestHandler;
use BeeJeeMVC\Lib\Handler\RoleRequestHandler;
use BeeJeeMVC\Lib\Paginator\PaginatorAdapterInterface;
use BeeJeeMVC\Lib\Paginator\PaginatorAdapter;
use BeeJeeMVC\Lib\Repository\TaskFileRepository;
use BeeJeeMVC\Lib\Repository\TaskPdoRepository;
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
     * @throws Exceptions\CannotBeEmptyException
     * @throws Exceptions\ForbiddenStatusException
     * @throws Exceptions\NotValidEmailException
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
        $filterHandler = new FilterRequestHandler();
        $accessHandler = new AccessRequestHandler($this->tokenManager);
        $roleHandler = new RoleRequestHandler();

        $filterHandler->setNextHandler($accessHandler)->setNextHandler($roleHandler);
        $filterHandler->handle($this->request);
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
            $this->createAdapter(),
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
        $taskPerPage = $_ENV['TASKS_PER_PAGE'];

        if ('sqlite' === $repositoryType) {
            $repository = new TaskPdoRepository($taskPerPage);
        } else {
            $repository = new TaskFileRepository(dirname(__DIR__).'/../'.$_ENV['TASK_FOLDER_NAME'], $taskPerPage);
        }

        return $repository;
    }

    /**
     * @return PaginatorAdapterInterface
     */
    private function createAdapter(): PaginatorAdapterInterface
    {
        return new PaginatorAdapter();
    }
}
