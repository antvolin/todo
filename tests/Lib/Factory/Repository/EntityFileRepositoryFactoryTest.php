<?php

namespace Tests\Lib\Factory\Repository;

use Todo\Lib\App;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Factory\Repository\EntityFileRepositoryFactory;
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
    public function shouldBeCreatedEntityFileRepository(): void
    {
        $app = new App();
        $factory = new EntityFileRepositoryFactory($app->getEntityName());
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

        $factory = new EntityFileRepositoryFactory('not valid entity name');
        $factory->create($this->entityPerPage);
    }
}
