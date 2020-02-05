<?php

namespace Tests\Lib;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\CannotCreateDirectoryException;

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

        $this->assertTrue(method_exists($factory, 'create'));
    }

    /**
     * @test
     */
    public function shouldBeGettingEntityService(): void
    {
        $service = $this->app->createEntityService();

        $this->assertTrue(method_exists($service, 'getCount'));
        $this->assertTrue(method_exists($service, 'remove'));
        $this->assertTrue(method_exists($service, 'done'));
        $this->assertTrue(method_exists($service, 'edit'));
        $this->assertTrue(method_exists($service, 'getCollection'));
        $this->assertTrue(method_exists($service, 'getById'));
        $this->assertTrue(method_exists($service, 'add'));
    }

    /**
     * @test
     *
     * @throws CannotCreateDirectoryException
     */
    public function shouldBeGettingRepository(): void
    {
        $repository = $this->app->createRepository();

        $this->assertTrue(method_exists($repository, 'getById'));
        $this->assertTrue(method_exists($repository, 'getCollection'));
        $this->assertTrue(method_exists($repository, 'getCount'));
        $this->assertTrue(method_exists($repository, 'add'));
        $this->assertTrue(method_exists($repository, 'remove'));
    }

    /**
     * @test
     */
    public function shouldBeGettingPaginatorFactory(): void
    {
        $factory = $this->app->createPaginatorFactory();

        $this->assertTrue(method_exists($factory, 'create'));
    }

    /**
     * @test
     */
    public function shouldBeGettingAuthService(): void
    {
        $service = $this->app->createAuthService($this->app->getRequest());

        $this->assertTrue(method_exists($service, 'getRequest'));
        $this->assertTrue(method_exists($service, 'logout'));
        $this->assertTrue(method_exists($service, 'login'));
    }
}
