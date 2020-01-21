<?php

namespace Todo\Tests\Lib\Factory\Service;

use Todo\Lib\App;
use Todo\Lib\Factory\Service\TokenServiceService;
use PHPUnit\Framework\TestCase;

class TokenServiceFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedTokenManager(): void
    {
        $app = new App();
        $request = $app->getRequest();
        $factory = new TokenServiceService($app->getTokenSalt());
        $tokenManager = $factory->create($request);

        $this->assertTrue(method_exists($tokenManager, 'getToken'));
        $this->assertTrue(method_exists($tokenManager, 'generateToken'));
        $this->assertTrue(method_exists($tokenManager, 'isValidToken'));
    }
}
