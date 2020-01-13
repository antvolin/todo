<?php

namespace BeeJeeMVC\Tests\Lib\Factory\Manager;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Factory\Manager\TokenManagerFactory;
use PHPUnit\Framework\TestCase;

class TokenManagerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedTokenManager(): void
    {
        $app = new App();
        $request = $app->getRequest();
        $factory = new TokenManagerFactory($app->getTokenSalt());
        $tokenManager = $factory->create($request);

        $token = $tokenManager->getToken();
        $secret = $request->getSession()->get('secret');
        $isValidToken = $tokenManager->isValidToken($token, $secret);

        $this->assertTrue($isValidToken);
    }
}
