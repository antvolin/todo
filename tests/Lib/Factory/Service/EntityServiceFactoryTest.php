<?php

namespace Tests\Lib\Factory\Service;

use Todo\Lib\App;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Factory\Service\EntityServiceFactory;
use PHPUnit\Framework\TestCase;

class EntityServiceFactoryTest extends TestCase
{
    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeCreatableEntityServiceWithValidName(): void
    {
        $factory = new EntityServiceFactory(App::getEntityClassNamespace());
        $entityService = $factory->create(App::getEntityName());

        $this->assertTrue(method_exists($entityService, 'getEntityName'));
        $this->assertTrue(method_exists($entityService, 'getEntityById'));
        $this->assertTrue(method_exists($entityService, 'getEntities'));
        $this->assertTrue(method_exists($entityService, 'getCountEntities'));
        $this->assertTrue(method_exists($entityService, 'createEntity'));
        $this->assertTrue(method_exists($entityService, 'editEntity'));
        $this->assertTrue(method_exists($entityService, 'doneEntity'));
        $this->assertTrue(method_exists($entityService, 'addEntity'));
        $this->assertTrue(method_exists($entityService, 'deleteEntity'));
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeNotCreatableEntityServiceWithNotValidName(): void
    {
        $this->expectException(NotAllowedEntityName::class);

        $factory = new EntityServiceFactory(App::getEntityClassNamespace());
        $factory->create('Not valid entity name');
    }
}
