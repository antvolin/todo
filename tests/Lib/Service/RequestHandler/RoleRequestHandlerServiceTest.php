<?php

namespace Tests\Lib\Service\RequestHandler;

use Todo\Lib\App;
use Todo\Lib\Service\RequestHandler\RoleRequestHandlerService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RoleRequestHandlerServiceTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider getModifyMethods
     *
     * @param string $method
     */
    public function shouldBeDenyAccessToTheModifyMethodsIfNotAdmin(string $method): void
    {
        $this->expectException(AccessDeniedHttpException::class);

        $app = new App();
        $request = $app->getRequest();
        $request->getSession()->set('admin', false);
        $request->server->set('REQUEST_URI', sprintf(App::getEntityName().'/%s', $method));
        $handler = new RoleRequestHandlerService();
        $handler->handle($request);
    }
    
    /**
     * @return array
     */
    public function getModifyMethods(): array
    {
        $data = [];

        foreach (RoleRequestHandlerService::MODIFY_METHODS as $method) {
            $data[] = [$method];
        }

        return $data;
    }
}
