<?php

namespace Tests\Lib\Service\RequestHandler;

use Todo\Lib\App;
use Todo\Lib\Service\RequestHandler\AccessRequestHandlerService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessRequestHandlerServiceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCheckedTheTokenForComplianceWithTheOriginalToken(): void
    {
        $this->expectException(AccessDeniedHttpException::class);

        $app = new App();
        $request = $app->getRequest();
        $request->query->set('csrf-token', App::getTokenSalt().':new value');
        $request->getSession()->set('secret', $app->getSecret());
        $handler = new AccessRequestHandlerService($app->getTokenServiceFactory());

        $handler->handle($request);
    }
}
