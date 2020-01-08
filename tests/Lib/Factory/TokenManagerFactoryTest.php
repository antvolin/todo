<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\Factory\RequestFactory;
use BeeJeeMVC\Lib\Factory\TokenManagerFactory;
use BeeJeeMVC\Lib\TokenManager;
use PHPUnit\Framework\TestCase;

class TokenManagerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedTokenManager(): void
    {
        $request = (new RequestFactory())->create();

        $tokenManager = (new TokenManagerFactory())->create($request);
        $this->assertInstanceOf(TokenManager::class, $tokenManager);

        $token = $tokenManager->getToken();
        $secret = $request->getSession()->get('secret');
        $isValidToken = $tokenManager->isValidToken($token, $secret);
        $this->assertTrue($isValidToken);
    }
}
