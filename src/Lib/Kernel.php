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
     * @throws Exceptions\CannotCreateDirectoryException
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->getRequest();
        $this->request->request->set('token', $this->app->createToken());
        $this->authService = $this->app->createAuthService($this->request);
        $this->entityService = $this->app->createEntityService();
        $this->entityService->setRepository($this->app->createRepository());
        $this->template = $this->app->createTemplate();
    }

	public function process(): void
    {
        $this->handleRequest();

        $separatedPath = PathService::separatePath($this->request->getPathInfo());
        $controller = $this->getController($separatedPath);
        $response = $this->getResponse($controller, $separatedPath);

        $response->send();
	}

	private function getController(array $separatedPath): ControllerInterface
    {
        $prefixClassName = strtolower(array_shift($separatedPath));

        if ('auth' === $prefixClassName) {
            $controller = $this->createAuthController();
        } else {
            $controller = $this->createEntityController();
        }

        return $controller;
    }

    private function getResponse(ControllerInterface $controller, array $separatedPath): Response
    {
        $methodName = array_shift($separatedPath);

        if (method_exists($controller, $methodName)) {
            /** @var Response $response */
            $response = $controller->$methodName(...$separatedPath);
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
                        $this->app->createPaginatorFactory(),
                        $this->entityService
                    )
                ),
                $this->app->createTokenServiceFactory()
            )
        );

        $requestHandler->handle($this->request);
    }

	private function createAuthController(): AuthController
    {
        return new AuthController(
            $this->authService,
            $this->template
        );
    }

    private function createEntityController(): EntityController
    {
        return new EntityController(
            $this->request,
            $this->entityService,
            $this->template
        );
    }
}
