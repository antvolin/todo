<?php

namespace BeeJeeMVC\Tests\Lib\Handler;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Handler\AccessRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessRequestHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCheckedTheTokenForComplianceWithTheOriginalToken(): void
    {
        $this->expectException(AccessDeniedHttpException::class);

        $app = new App();
        $request = $app->getRequest();
        $request->query->set('csrf-token', $_ENV['TOKEN_SALT'].':new value');
        $request->getSession()->set('secret', $app->getSecret());

        (new AccessRequestHandler($app->getTokenManagerFactory()))->handle($request);
    }
}
