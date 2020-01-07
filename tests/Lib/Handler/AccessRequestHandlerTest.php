<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\Factory\RequestFactory;
use BeeJeeMVC\Lib\Handler\AccessRequestHandler;
use BeeJeeMVC\Lib\SecretGenerator;
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

        $request = (new RequestFactory())->create();
        $request->query->set('csrf-token', $_ENV['TOKEN_SALT'].':new value');
        $request->getSession()->set('secret', (new SecretGenerator())->generateSecret());

        (new AccessRequestHandler())->handle($request);
    }
}
