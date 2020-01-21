<?php

namespace Todo\Lib;

use Todo\Controller\AuthController;
use Todo\Controller\EntityController;
use Todo\Lib\Service\AuthService;
use Todo\Lib\RequestHandler\AccessRequestHandler;
use Todo\Lib\RequestHandler\FilterRequestHandler;
use Todo\Lib\RequestHandler\PaginatorRequestHandler;
use Todo\Lib\RequestHandler\RoleRequestHandler;
use Todo\Lib\Service\EntityService;
use Todo\Lib\Service\PathService;
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
     * @var Environment
     */
    private $template;

    /**
     * @var EntityService
     */
    private $entityManager;

    /**
     * @var App
     */
    private $app;

    /**
     * @var AuthService
     */
    private $authService;

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
        $this->request->request->set('token', $this->app->getToken());
        $this->authService = $this->app->getAuthService($this->request);
        $this->template = $this->app->getTemplate();
        $this->entityManager = $this->app->getEntityManager();

        $this->handleRequest();

        $urlParts = PathService::getPathParts($this->request->getPathInfo());

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
            $this->authService,
            $this->template
        );
    }

    /**
     * @return EntityController
     */
    private function createEntityController(): EntityController
    {
        return new EntityController(
            $this->request,
            $this->entityManager,
            $this->template
        );
    }
}
