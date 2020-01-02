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
     * @throws Exceptions\CannotBeEmptyException
     * @throws Exceptions\ForbiddenStatusException
     * @throws Exceptions\NotValidEmailException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
	public function process(): void
    {
        $request = Request::createFromGlobals();
        $request->setSession($this->initSession());

        $tokenManager = new TokenManager();
        $tokenManager->generateToken($this->initSecretKey($request));
        $this->handleRequest($tokenManager, $request);

        $urlParts = explode('/', trim($request->getPathInfo(), '/'));

        if ('auth' === strtolower(array_shift($urlParts))) {
            $controller = new AuthController(
                $request,
                $tokenManager->getToken(),
                $this->createTemplate()
            );
        } else {
            $controller = new TaskController(
                $request,
                new TaskManager($this->createRepo()),
                $this->createAdapter(),
                $tokenManager->getToken(),
                new Ordering(),
                $this->createTemplate()
            );
        }

        if (method_exists($controller, $action = array_shift($urlParts))) {
            /** @var Response $response */
            $response = $controller->$action(...$urlParts);
        } else {
            $response = $controller->list();
        }

        $response->send();
	}

    /**
     * @param TokenManager $tokenManager
     * @param Request $request
     */
	private function handleRequest(TokenManager $tokenManager, Request $request): void
    {
        $filterHandler = new FilterRequestHandler();
        $accessHandler = new AccessRequestHandler($tokenManager);
        $roleHandler = new RoleRequestHandler();

        $filterHandler->setNextHandler($accessHandler)->setNextHandler($roleHandler);
        $filterHandler->handle($request);
    }

    /**
     * @return Session
     */
	private function initSession(): Session
    {
        $session = new Session();
        $session->start();

        return $session;
    }

    /**
     * @return Environment
     */
	private function createTemplate(): Environment
    {
        $loader = new FilesystemLoader(dirname(__DIR__).'/../'.'templates');
        $options = ['autoescape' => false];

        return new Environment($loader, $options);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
	private function initSecretKey(Request $request): string
    {
        if (!$request->getSession()->get('secret')) {
            $secret = (new SecretGenerator())->generateSecret();

            $request->getSession()->set('secret', $secret);
        } else {
            $secret = $request->getSession()->get('secret');
        }

        return $secret;
    }

    /**
     * @return PaginatorAdapterInterface
     */
    private function createAdapter(): PaginatorAdapterInterface
    {
        return new PaginatorAdapter();
    }

    /**
     * @return TaskRepositoryInterface
     */
    private function createRepo(): TaskRepositoryInterface
    {
        $repoType = $_ENV['REPOSITORY'];
        $taskPerPage = $_ENV['TASKS_PER_PAGE'];

        if ('sqlite' === $repoType) {
            $repo = new TaskPdoRepository($taskPerPage);
        }
        if ('file' === $repoType) {
            $repo = new TaskFileRepository(dirname(__DIR__).'/../'.$_ENV['TASK_FOLDER_NAME'], $taskPerPage);
        }

        return $repo;
    }
}
