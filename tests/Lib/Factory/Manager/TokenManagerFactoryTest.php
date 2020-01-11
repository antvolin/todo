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
        $request = (new App())->getRequest();
        $factory = new TokenManagerFactory($_ENV['TOKEN_SALT']);
        $tokenManager = $factory->create($request);

        $token = $tokenManager->getToken();
        $secret = $request->getSession()->get('secret');
        $isValidToken = $tokenManager->isValidToken($token, $secret);

        $this->assertTrue($isValidToken);
    }
}
