<?php

namespace Tests\Lib\Factory\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Todo\Lib\App;
use Todo\Lib\Factory\Service\TokenServiceFactory;
use Todo\Lib\Service\Token\TokenService;

class TokenServiceFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatableTokenService(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $request->method('getSession')->willReturn($session);

        $factory = new TokenServiceFactory(App::getTokenSalt());
        $factory->setRequest($request);
        $service = $factory->createService();

        $this->assertInstanceOf(TokenService::class, $service);
    }
}
