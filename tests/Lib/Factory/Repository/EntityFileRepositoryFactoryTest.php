<?php

namespace Tests\Lib\Factory\Repository;

use Todo\Lib\App;
use Todo\Lib\Factory\Repository\EntityFileRepositoryFactory;
use PHPUnit\Framework\TestCase;

class EntityFileRepositoryFactoryTest extends TestCase
{
    /**
     * @var int
     */
    private $entityPerPage;

    /**
     * @var string
     */
    private $entityName;

    protected function setUp()
    {
        $this->entityPerPage = 3;
        $this->entityName = App::getEntityName();
    }

    /**
     * @test
     */
    public function shouldBeCreatableEntityFileRepository(): void
    {
        $factory = new EntityFileRepositoryFactory();
        $repository = $factory->create($this->entityPerPage, $this->entityName);

        $this->assertTrue(method_exists($repository, 'getCountEntities'));
        $this->assertTrue(method_exists($repository, 'getEntities'));
        $this->assertTrue(method_exists($repository, 'addEntity'));
        $this->assertTrue(method_exists($repository, 'getEntityById'));
    }
}
