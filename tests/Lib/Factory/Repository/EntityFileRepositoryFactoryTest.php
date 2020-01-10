<?php

namespace BeeJeeMVC\Tests\Lib\Factory\Repository;

use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Factory\Repository\EntityFileRepositoryFactory;
use BeeJeeMVC\Lib\Repository\EntityFileRepository;
use PHPUnit\Framework\TestCase;

class EntityFileRepositoryFactoryTest extends TestCase
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
    public function shouldBeCreatedTaskFileRepository(): void
    {
        $repository = (new EntityFileRepositoryFactory($_ENV['ENTITY_NAME']))->create($this->entityPerPage);

        $this->assertInstanceOf(EntityFileRepository::class, $repository);
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
        (new EntityFileRepositoryFactory('not valid entity name'))->create($this->entityPerPage);
    }
}
