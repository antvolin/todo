<?php

namespace Tests\Lib;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\PdoConnectionException as PdoConnectionExceptionAlias;

class AppTest extends TestCase
{
    /**
     * @var App
     */
    private $app;

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
        $secret = $this->app->getSecret();

        $this->assertIsString($secret);
        $this->assertNotEmpty($secret);
    }

    /**
     * @test
     */
    public function shouldBeGettingToken(): void
    {
        $token = $this->app->getToken();

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

    /**
     * @test
     */
    public function shouldBeGettingTokenServiceFactory(): void
    {
        $factory = $this->app->getTokenServiceFactory();

        $this->assertTrue(method_exists($factory, 'create'));
    }

    /**
     * @test
     */
    public function shouldBeGettingEntityService(): void
    {
        $service = $this->app->getEntityService();

        $this->assertTrue(method_exists($service, 'getCountEntities'));
        $this->assertTrue(method_exists($service, 'deleteEntity'));
        $this->assertTrue(method_exists($service, 'doneEntity'));
        $this->assertTrue(method_exists($service, 'editEntity'));
        $this->assertTrue(method_exists($service, 'getEntities'));
        $this->assertTrue(method_exists($service, 'getEntityById'));
        $this->assertTrue(method_exists($service, 'addEntity'));
    }

    /**
     * @test
     *
     * @throws PdoConnectionExceptionAlias
     */
    public function shouldBeGettingRepository(): void
    {
        $repository = $this->app->getRepository();

        $this->assertTrue(method_exists($repository, 'getById'));
        $this->assertTrue(method_exists($repository, 'getCollection'));
        $this->assertTrue(method_exists($repository, 'getCount'));
        $this->assertTrue(method_exists($repository, 'add'));
        $this->assertTrue(method_exists($repository, 'remove'));
    }

    /**
     * @test
     *
     * @throws PdoConnectionExceptionAlias
     */
    public function shouldBeGettingRepositoryFactory(): void
    {
        $factory = $this->app->getRepositoryFactory();

        $this->assertTrue(method_exists($factory, 'create'));
    }

    /**
     * @test
     *
     * @throws PdoConnectionExceptionAlias
     */
    public function shouldBeGettingPdo(): void
    {
        $this->markTestSkipped();

        $pdo = $this->app->getPdo();

        $this->assertTrue(method_exists($pdo, 'query'));
        $this->assertTrue(method_exists($pdo, 'exec'));
        $this->assertTrue(method_exists($pdo, 'prepare'));
        $this->assertTrue(method_exists($pdo, 'lastInsertId'));
    }

    /**
     * @test
     */
    public function shouldBeGettingPaginatorFactory(): void
    {
        $factory = $this->app->getPaginatorFactory();

        $this->assertTrue(method_exists($factory, 'create'));
    }

    /**
     * @test
     */
    public function shouldBeGettingAuthService(): void
    {
        $service = $this->app->getAuthService($this->app->getRequest());

        $this->assertTrue(method_exists($service, 'getRequest'));
        $this->assertTrue(method_exists($service, 'logout'));
        $this->assertTrue(method_exists($service, 'login'));
    }
}
