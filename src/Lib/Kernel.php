<?php

namespace Todo\Lib;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Todo\Controller\AuthController;
use Todo\Controller\EntityController;
use Todo\Lib\Factory\Template\TemplateAdapterInterface;
use Todo\Lib\Service\Auth\AuthServiceInterface;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Service\Path\PathService;
use Todo\Lib\Service\RequestHandler\AccessRequestHandlerService;
use Todo\Lib\Service\RequestHandler\FilterRequestHandlerService;
use Todo\Lib\Service\RequestHandler\PaginatorRequestHandlerService;
use Todo\Lib\Service\RequestHandler\RoleRequestHandlerService;

class Kernel
{
    private App $app;
    private Request $request;
    private AuthServiceInterface $authService;
    private EntityServiceInterface $entityService;
    private TemplateAdapterInterface $template;

    /**
     * @param App $app
     *
     * @throws Exceptions\PdoConnectionException
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->getRequest();
        $this->request->request->set('token', $this->app->getToken());
        $this->authService = $this->app->getAuthService($this->request);
        $this->entityService = $this->app->getEntityService();
        $this->entityService->setRepository($this->app->getRepository());
        $this->template = $this->app->getTemplate();
    }

	public function process(): void
    {
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
        $requestHandler = new FilterRequestHandlerService(
            new AccessRequestHandlerService(
                new RoleRequestHandlerService(
                    new PaginatorRequestHandlerService(
                        $this->app->getPaginatorFactory(),
                        $this->entityService
                    )
                ),
                $this->app->getTokenServiceFactory()
            )
        );
//        $accessRequestHandler = new AccessRequestHandlerService(
//            $this->app->getTokenServiceFactory()
//        );
//        $roleRequestHandler = new RoleRequestHandlerService();
//        $pagingRequestHandler = new PaginatorRequestHandlerService(
//            $this->app->getPaginatorFactory(),
//            $this->entityService
//        );

//        $filterRequestHandler
//            ->setNextHandler($accessRequestHandler)
//            ->setNextHandler($roleRequestHandler)
//            ->setNextHandler($pagingRequestHandler);

        $requestHandler->handle($this->request);
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
            $this->template
        );
    }
}
