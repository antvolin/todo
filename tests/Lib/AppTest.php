<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Factory\Manager\TokenManagerFactoryInterface;
use BeeJeeMVC\Lib\Factory\Paginator\PaginatorFactoryInterface;
use BeeJeeMVC\Lib\Factory\Repository\EntityRepositoryFactory;
use BeeJeeMVC\Lib\Manager\EntityManagerInterface;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use PDO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AppTest extends TestCase
{
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
        $this->assertInstanceOf(Request::class, $this->app->getRequest());
    }

    /**
     * @test
     */
    public function shouldBeGettingSecret(): void
    {
        $secret = $this->app->getSecret();
        $this->assertIsString(Request::class, $secret);
        $this->assertNotEmpty($secret);
    }

    /**
     * @test
     */
    public function shouldBeGettingToken(): void
    {
        $token = $this->app->getToken();
        $this->assertIsString(Request::class, $token);
        $this->assertNotEmpty($token);
    }

    /**
     * @test
     */
    public function shouldBeGettingTokenManagerFactory(): void
    {
        $factory = $this->app->getTokenManagerFactory();
        $this->assertInstanceOf(TokenManagerFactoryInterface::class, $factory);
    }

    /**
     * @test
     * @throws NotAllowedEntityName
     */
    public function shouldBeGettingEntityManager(): void
    {
        $manager = $this->app->getEntityManager();
        $this->assertInstanceOf(EntityManagerInterface::class, $manager);
    }

    /**
     * @test
     * @throws NotAllowedEntityName
     */
    public function shouldBeGettingRepository(): void
    {
        $repository = $this->app->getRepository();
        $this->assertInstanceOf(EntityRepositoryInterface::class, $repository);
    }

    /**
     * @test
     * @throws NotAllowedEntityName
     */
    public function shouldBeGettingRepositoryFactory(): void
    {
        $factory = $this->app->getRepositoryFactory();
        $this->assertInstanceOf(EntityRepositoryFactory::class, $factory);
    }

    /**
     * @test
     */
    public function shouldBeGettingPdo(): void
    {
        $pdo = $this->app->getPdo();
        $this->assertInstanceOf(Pdo::class, $pdo);
    }

    /**
     * @test
     */
    public function shouldBeGettingPaginatorFactory(): void
    {
        $factory = $this->app->getPaginatorFactory();
        $this->assertInstanceOf(PaginatorFactoryInterface::class, $factory);
    }
}
