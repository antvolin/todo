<?php

namespace Tests\Lib\Factory\Repository;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Factory\Repository\EntityFileRepositoryFactory;

class EntityFileRepositoryFactoryTest extends TestCase
{
    private int $entityPerPage;
    private string $entityName;

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

        $this->assertTrue(method_exists($repository, 'getById'));
        $this->assertTrue(method_exists($repository, 'getCollection'));
        $this->assertTrue(method_exists($repository, 'getCount'));
        $this->assertTrue(method_exists($repository, 'add'));
        $this->assertTrue(method_exists($repository, 'remove'));
    }
}
