<?php

namespace Tests\Lib\Factory\Service;

use Todo\Lib\App;
use Todo\Lib\Factory\Service\TokenServiceFactory;
use PHPUnit\Framework\TestCase;

class TokenServiceFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedTokenService(): void
    {
        $app = new App();
        $request = $app->getRequest();
        $factory = new TokenServiceFactory($app->getTokenSalt());
        $service = $factory->create($request);

        $this->assertTrue(method_exists($service, 'getToken'));
        $this->assertTrue(method_exists($service, 'generateToken'));
        $this->assertTrue(method_exists($service, 'isValidToken'));
    }
}
