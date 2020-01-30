<?php

namespace Todo\Lib;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Todo\Controller\AuthController;
use Todo\Controller\ControllerInterface;
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

        $urlParts = PathService::separatePath($this->request->getPathInfo());
        $controller = $this->getController($urlParts);
        $response = $this->getResponse($controller, $urlParts);

        $response->send();
	}

    /**
     * @param array $urlParts
     *
     * @return ControllerInterface
     */
	private function getController(array $urlParts): ControllerInterface
    {
        $prefixClassName = strtolower(array_shift($urlParts));

        if ('auth' === $prefixClassName) {
            $controller = $this->createAuthController();
        } else {
            $controller = $this->createEntityController();
        }

        return $controller;
    }

    /**
     * @param ControllerInterface $controller
     * @param array $urlParts
     *
     * @return Response
     */
    private function getResponse(ControllerInterface $controller, array $urlParts): Response
    {
        $methodName = array_shift($urlParts);

        if (method_exists($controller, $methodName)) {
            /** @var Response $response */
            $response = $controller->$methodName(...$urlParts);
        } else {
            $response = $controller->list();
        }

        return $response;
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
