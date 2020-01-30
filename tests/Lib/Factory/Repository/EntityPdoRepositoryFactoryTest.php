<?php

namespace Tests\Lib\Factory\Repository;

use PDO;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Factory\Repository\EntityPdoRepositoryFactory;

class EntityPdoRepositoryFactoryTest extends TestCase
{
    private int $entityPerPage;
    private string $entityName;
    private MockObject $pdo;

    protected function setUp()
    {
        $this->entityPerPage = 3;
        $this->entityName = App::getEntityName();
        $this->pdo = $this->createMock(PDO::class);
    }

    /**
     * @test
     */
    public function shouldBeCreatableEntityPdoRepository(): void
    {
        $entityFactory = $this->createMock(EntityFactoryInterface::class);
        $repositoryFactory = new EntityPdoRepositoryFactory($this->pdo, $entityFactory);

        $repository = $repositoryFactory->create($this->entityPerPage, $this->entityName);

        $this->assertTrue(method_exists($repository, 'getById'));
        $this->assertTrue(method_exists($repository, 'getCollection'));
        $this->assertTrue(method_exists($repository, 'getCount'));
        $this->assertTrue(method_exists($repository, 'add'));
        $this->assertTrue(method_exists($repository, 'remove'));
    }
}
