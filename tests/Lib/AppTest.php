<?php

namespace Tests\Lib;

use Todo\Lib\App;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    /**
     * @var App
     */
    protected $app;

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
    public function shouldBeGettingTokenManagerFactory(): void
    {
        $factory = $this->app->getTokenManagerFactory();

        $this->assertTrue(method_exists($factory, 'create'));
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeGettingEntityManager(): void
    {
        $manager = $this->app->getEntityManager();

        $this->assertTrue(method_exists($manager, 'getCountEntities'));
        $this->assertTrue(method_exists($manager, 'deleteEntity'));
        $this->assertTrue(method_exists($manager, 'doneEntity'));
        $this->assertTrue(method_exists($manager, 'editEntity'));
        $this->assertTrue(method_exists($manager, 'getEntities'));
        $this->assertTrue(method_exists($manager, 'getEntityById'));
        $this->assertTrue(method_exists($manager, 'saveEntity'));
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeGettingRepository(): void
    {
        $repository = $this->app->getRepository();

        $this->assertTrue(method_exists($repository, 'getCountEntities'));
        $this->assertTrue(method_exists($repository, 'getEntities'));
        $this->assertTrue(method_exists($repository, 'saveEntity'));
        $this->assertTrue(method_exists($repository, 'getEntityById'));
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeGettingRepositoryFactory(): void
    {
        $factory = $this->app->getRepositoryFactory();

        $this->assertTrue(method_exists($factory, 'create'));
    }

    /**
     * @test
     */
    public function shouldBeGettingPdo(): void
    {
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
