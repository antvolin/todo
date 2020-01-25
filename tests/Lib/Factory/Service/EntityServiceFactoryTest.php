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
    public function shouldBeCreatedEntityManager(): void
    {
        $app = new App();
        $factory = new EntityServiceFactory($app->getEntityClassNamespace());
        $entityManager = $factory->create($app->getEntityName(), $app->getRepository());

        $this->assertTrue(method_exists($entityManager, 'deleteEntity'));
        $this->assertTrue(method_exists($entityManager, 'doneEntity'));
        $this->assertTrue(method_exists($entityManager, 'editEntity'));
        $this->assertTrue(method_exists($entityManager, 'saveEntity'));
        $this->assertTrue(method_exists($entityManager, 'getEntities'));
        $this->assertTrue(method_exists($entityManager, 'getCountEntities'));
        $this->assertTrue(method_exists($entityManager, 'getEntityById'));
    }
}
