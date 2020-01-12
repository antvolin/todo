<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Controller\AuthController;
use BeeJeeMVC\Controller\EntityController;
use BeeJeeMVC\Lib\RequestHandler\AccessRequestHandler;
use BeeJeeMVC\Lib\RequestHandler\FilterRequestHandler;
use BeeJeeMVC\Lib\RequestHandler\PaginatorRequestHandler;
use BeeJeeMVC\Lib\RequestHandler\RoleRequestHandler;
use BeeJeeMVC\Lib\Manager\EntityManager;
use BeeJeeMVC\Lib\Manager\PathManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
     * @var EntityManager
     */
    private $entityManager;

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
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exceptions\NotAllowedEntityName
     */
	public function process(): void
    {
        $this->request = $this->app->getRequest();
        $this->token = $this->app->getToken();
        $this->template = $this->app->getTemplate();
        $this->entityManager = $this->app->getEntityManager();

        $this->handleRequest();

        $urlParts = PathManager::getPathParts($this->request->getPathInfo());

        if ('auth' === strtolower(array_shift($urlParts))) {
            $controller = $this->createAuthController();
        } else {
            $controller = $this->createEntityController();
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
            $this->entityManager
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
     * @return EntityController
     */
    private function createEntityController(): EntityController
    {
        return new EntityController(
            $this->token,
            $this->request,
            $this->entityManager,
            $this->template
        );
    }
}
