<?php

namespace BeeJeeMVC\Tests\Lib\Factory\Repository;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Factory\Repository\EntityPdoRepositoryFactory;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use PDO;
use PHPUnit\Framework\TestCase;

class EntityPdoRepositoryFactoryTest extends TestCase
{
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
        $this->entityPerPage = 3;
        $this->pdo = (new App())->getPdo();
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeCreatedEntityPdoRepository(): void
    {
        $factory = new EntityPdoRepositoryFactory($this->pdo, $_ENV['ENTITY_NAME'], $_ENV['ENTITY_CLASS_NAMESPACE']);
        $repository = $factory->create($this->entityPerPage);

        $this->assertInstanceOf(EntityRepositoryInterface::class, $repository);
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
