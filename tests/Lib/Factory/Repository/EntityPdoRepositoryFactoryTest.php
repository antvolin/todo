<?php

namespace Todo\Tests\Lib\Factory\Repository;

use Todo\Lib\App;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Factory\Repository\EntityPdoRepositoryFactory;
use PDO;
use PHPUnit\Framework\TestCase;

class EntityPdoRepositoryFactoryTest extends TestCase
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var int
     */
    protected $entityPerPage;

    /**
     * @var Pdo
     */
    protected $pdo;

    protected function setUp()
    {
        $this->app = new App();
        $this->entityPerPage = 3;
        $this->pdo = $this->app->getPdo();
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeCreatedEntityPdoRepository(): void
    {
        $factory = new EntityPdoRepositoryFactory($this->pdo, $this->app->getEntityName(), $this->app->getEntityClassNamespace());
        $repository = $factory->create($this->entityPerPage);

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
    public function shouldBeNotCreatedWithNotValidEntityName(): void
    {
        $this->expectException(NotAllowedEntityName::class);

        $factory = new EntityPdoRepositoryFactory($this->pdo, 'not valid entity name', 'asd');
        $factory->create($this->entityPerPage);
    }
}
