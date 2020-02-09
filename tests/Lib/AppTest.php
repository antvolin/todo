<?php

namespace Tests\Lib;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotCreateDirectoryException;
use Todo\Lib\Exceptions\PdoConnectionException;
use Todo\Lib\Exceptions\RedisConnectionException;
use Todo\Lib\Factory\Paginator\PaginatorFactoryInterface;
use Todo\Lib\Factory\Service\TokenServiceFactory;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Lib\Service\Auth\AuthService;
use Todo\Lib\Service\Entity\EntityService;

class AppTest extends TestCase
{
    private App $app;

    protected function setUp()
    {
        $this->app = new App();
    }

    /**
     * @test
     */
    public function shouldBeGettingRequestObject(): void
    {
        $request = $this->app->getRequest();

        $this->assertObjectHasAttribute('request', $request);
        $this->assertObjectHasAttribute('query', $request);
    }

    /**
     * @test
     */
    public function shouldBeGettingSecret(): void
    {
        $secret = $this->app->createSecret();

        $this->assertIsString($secret);
        $this->assertNotEmpty($secret);
    }

    /**
     * @test
     */
    public function shouldBeGettingToken(): void
    {
        $token = $this->app->createToken();

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

    /**
     * @test
     */
    public function shouldBeGettingTokenServiceFactory(): void
    {
        $factory = $this->app->createTokenServiceFactory();

        $this->assertInstanceOf(TokenServiceFactory::class, $factory);
    }

    /**
     * @test
     */
    public function shouldBeGettingEntityService(): void
    {
        $service = $this->app->createEntityService();

        $this->assertInstanceOf(EntityService::class, $service);
    }

    /**
     * @test
     *
     * @throws CannotCreateDirectoryException
     * @throws PdoConnectionException
     * @throws RedisConnectionException
     */
    public function shouldBeGettingRepository(): void
    {
        $repository = $this->app->createRepository();

        $this->assertInstanceOf(EntityRepositoryInterface::class, $repository);
    }

    /**
     * @test
     */
    public function shouldBeGettingPaginatorFactory(): void
    {
        $factory = $this->app->createPaginatorFactory();

        $this->assertInstanceOf(PaginatorFactoryInterface::class, $factory);
    }

    /**
     * @test
     */
    public function shouldBeGettingAuthService(): void
    {
        $service = $this->app->createAuthService($this->app->getRequest());

        $this->assertInstanceOf(AuthService::class, $service);
    }
}
