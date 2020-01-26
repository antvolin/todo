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
        $app = new App();
        $factory = new EntityServiceFactory($app->getEntityClassNamespace());
        $entityManager = $factory->create($app->getEntityName());

        $this->assertTrue(method_exists($entityManager, 'getEntityName'));
        $this->assertTrue(method_exists($entityManager, 'getEntityById'));
        $this->assertTrue(method_exists($entityManager, 'getEntities'));
        $this->assertTrue(method_exists($entityManager, 'getCountEntities'));
        $this->assertTrue(method_exists($entityManager, 'createEntity'));
        $this->assertTrue(method_exists($entityManager, 'editEntity'));
        $this->assertTrue(method_exists($entityManager, 'doneEntity'));
        $this->assertTrue(method_exists($entityManager, 'addEntity'));
        $this->assertTrue(method_exists($entityManager, 'deleteEntity'));
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeNotCreatableEntityServiceWithNotValidName(): void
    {
        $this->expectException(NotAllowedEntityName::class);

        $app = new App();
        $factory = new EntityServiceFactory($app->getEntityClassNamespace());
        $factory->create('Not valid entity name');
    }
}
