<?php

namespace Todo\Lib;

use Todo\Controller\AuthController;
use Todo\Controller\EntityController;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Lib\Service\Auth\AuthService;
use Todo\Lib\Service\Entity\EntityService;
use Todo\Lib\Service\Path\PathService;
use Todo\Lib\Service\RequestHandler\AccessRequestHandlerService;
use Todo\Lib\Service\RequestHandler\FilterRequestHandlerService;
use Todo\Lib\Service\RequestHandler\PaginatorRequestHandlerService;
use Todo\Lib\Service\RequestHandler\RoleRequestHandlerService;
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
    private $entityService;

    /**
     * @var EntityRepositoryInterface
     */
    private $entityRepository;

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
     * @throws Exceptions\PdoConnectionException
     */
	public function process(): void
    {
        $this->request = $this->app->getRequest();
        $this->request->request->set('token', $this->app->getToken());
        $this->authService = $this->app->getAuthService($this->request);
        $this->template = $this->app->getTemplate();
        $this->entityRepository = $this->app->getRepository();
        $this->entityService = $this->app->getEntityService();

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
        $filterRequestHandler = new FilterRequestHandlerService();
        $accessRequestHandler = new AccessRequestHandlerService(
            $this->app->getTokenServiceFactory()
        );
        $roleRequestHandler = new RoleRequestHandlerService();
        $pagingRequestHandler = new PaginatorRequestHandlerService(
            $this->app->getPaginatorFactory(),
            $this->entityService,
            $this->entityRepository
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
            $this->entityService,
            $this->entityRepository,
            $this->template
        );
    }
}
