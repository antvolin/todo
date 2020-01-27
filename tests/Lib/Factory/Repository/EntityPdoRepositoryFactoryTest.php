<?php

namespace Tests\Lib\Factory\Repository;

use PDO;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Factory\Repository\EntityPdoRepositoryFactory;

class EntityPdoRepositoryFactoryTest extends TestCase
{
    /**
     * @var int
     */
    private $entityPerPage;

    /**
     * @var MockObject
     */
    private $pdo;

    protected function setUp()
    {
        $this->entityPerPage = 3;
        $this->pdo = $this->createMock(PDO::class);
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeCreatableEntityPdoRepository(): void
    {
        $service = $this->createMock(EntityFactoryInterface::class);
        $service->method('getEntityName')->willReturn(App::getEntityName());

        $factory = new EntityPdoRepositoryFactory($this->pdo, $service);
        $repository = $factory->create($this->entityPerPage);

        $this->assertTrue(method_exists($repository, 'getEntityById'));
        $this->assertTrue(method_exists($repository, 'getCountEntities'));
        $this->assertTrue(method_exists($repository, 'getEntities'));
        $this->assertTrue(method_exists($repository, 'addEntity'));
        $this->assertTrue(method_exists($repository, 'deleteEntity'));
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeNotCreatableWithNotValidEntityName(): void
    {
        $this->expectException(NotAllowedEntityName::class);

        $service = $this->createMock(EntityFactoryInterface::class);
        $service->method('getEntityName')->willReturn('dsadasd');

        $factory = new EntityPdoRepositoryFactory($this->pdo, $service);
        $factory->create($this->entityPerPage);
    }
}
