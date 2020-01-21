<?php

namespace Todo\Tests\Lib\Handler;

use Todo\Lib\App;
use Todo\Lib\RequestHandler\AccessRequestHandler;
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
        $request->query->set('csrf-token', $app->getTokenSalt().':new value');
        $request->getSession()->set('secret', $app->getSecret());
        $handler = new AccessRequestHandler($app->getTokenManagerFactory());

        $handler->handle($request);
    }
}
