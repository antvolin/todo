<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Handler\RoleRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RoleRequestHandlerTest extends TestCase
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
        $request->server->set('REQUEST_URI', sprintf('task/%s', $method));

        (new RoleRequestHandler())->handle($request);
    }
    
    /**
     * @return array
     */
    public function getModifyMethods(): array
    {
        $data = [];

        foreach (RoleRequestHandler::MODIFY_METHODS as $method) {
            $data[] = [$method];
        }

        return $data;
    }
}
