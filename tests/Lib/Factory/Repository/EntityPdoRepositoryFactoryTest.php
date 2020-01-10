<?php

namespace BeeJeeMVC\Tests\Lib\Factory\Repository;

use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Factory\Repository\EntityPdoRepositoryFactory;
use BeeJeeMVC\Lib\Repository\EntityPdoRepository;
use PHPUnit\Framework\TestCase;

class EntityPdoRepositoryFactoryTest extends TestCase
{
    /**
     * @var int
     */
    protected $entityPerPage;

    protected function setUp()
    {
        $this->entityPerPage = 3;
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeCreatedTaskPdoRepository(): void
    {
        $repository = (new EntityPdoRepositoryFactory($_ENV['ENTITY_NAME'], $_ENV['PDO_TYPE'], $_ENV['DB_FOLDER_NAME'], $_ENV['ENTITY_FOLDER_NAMESPACE']))->create($this->entityPerPage);

        $this->assertInstanceOf(EntityPdoRepository::class, $repository);
        $this->assertTrue(method_exists($repository, 'getById'));
        $this->assertTrue(method_exists($repository, 'getCountRows'));
        $this->assertTrue(method_exists($repository, 'getList'));
        $this->assertTrue(method_exists($repository, 'save'));
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeNotCreatedWithNotValidEntityName(): void
    {
        $this->expectException(NotAllowedEntityName::class);
        (new EntityPdoRepositoryFactory('not valid entity name', 'asd', 'asd', 'asd'))->create($this->entityPerPage);
    }
}
