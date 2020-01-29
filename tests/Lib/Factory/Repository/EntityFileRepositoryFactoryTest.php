<?php

namespace Tests\Lib\Factory\Repository;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Factory\Repository\EntityFileRepositoryFactoryInterface;

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
        $factory = new EntityFileRepositoryFactoryInterface();
        $repository = $factory->create($this->entityPerPage, $this->entityName);

        $this->assertTrue(method_exists($repository, 'getById'));
        $this->assertTrue(method_exists($repository, 'getCollection'));
        $this->assertTrue(method_exists($repository, 'getCount'));
        $this->assertTrue(method_exists($repository, 'add'));
        $this->assertTrue(method_exists($repository, 'remove'));
    }
}
