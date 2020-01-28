<?php

namespace Tests\Lib\Factory\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Todo\Lib\App;
use Todo\Lib\Factory\Service\TokenServiceFactory;

class TokenServiceFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatableTokenService(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $request->method('getSession')
            ->willReturn($session);

        $factory = new TokenServiceFactory(App::getTokenSalt());
        $service = $factory->create($request);

        $this->assertTrue(method_exists($service, 'getToken'));
        $this->assertTrue(method_exists($service, 'generateToken'));
        $this->assertTrue(method_exists($service, 'isValidToken'));
    }
}
