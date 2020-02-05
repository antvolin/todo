<?php

namespace Tests\Lib\Factory\Service;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Factory\Service\EntityServiceFactory;

class EntityServiceFactoryTest extends TestCase
{
    private EntityFactoryInterface $entityFactory;

    protected function setUp()
    {
        $app = new App();
        $this->entityFactory = $app->createEntityFactory();
    }

    /**
     * @test
     */
    public function shouldBeCreatableEntityServiceWithValidName(): void
    {
        $factory = new EntityServiceFactory($this->entityFactory);
        $entityService = $factory->create();

        $this->assertTrue(method_exists($entityService, 'getById'));
        $this->assertTrue(method_exists($entityService, 'getCollection'));
        $this->assertTrue(method_exists($entityService, 'getCount'));
        $this->assertTrue(method_exists($entityService, 'edit'));
        $this->assertTrue(method_exists($entityService, 'done'));
        $this->assertTrue(method_exists($entityService, 'add'));
        $this->assertTrue(method_exists($entityService, 'remove'));
    }
}
