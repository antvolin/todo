<?php

namespace Tests\Lib\Factory\Service;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Factory\Service\EntityServiceFactory;

class EntityServiceFactoryTest extends TestCase
{
    /**
     * @var EntityFactoryInterface
     */
    private $entityFactory;

    protected function setUp()
    {
        $app = new App();
        $this->entityFactory = $app->getEntityFactory();
    }

    /**
     * @test
     */
    public function shouldBeCreatableEntityServiceWithValidName(): void
    {
        $factory = new EntityServiceFactory($this->entityFactory);
        $entityService = $factory->create();

        $this->assertTrue(method_exists($entityService, 'getEntityById'));
        $this->assertTrue(method_exists($entityService, 'getEntities'));
        $this->assertTrue(method_exists($entityService, 'getCountEntities'));
        $this->assertTrue(method_exists($entityService, 'createEntity'));
        $this->assertTrue(method_exists($entityService, 'editEntity'));
        $this->assertTrue(method_exists($entityService, 'doneEntity'));
        $this->assertTrue(method_exists($entityService, 'addEntity'));
        $this->assertTrue(method_exists($entityService, 'deleteEntity'));
    }
}
