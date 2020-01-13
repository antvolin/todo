<?php

namespace BeeJeeMVC\Tests\Lib\Factory\Manager;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Factory\Manager\EntityManagerFactory;
use BeeJeeMVC\Lib\Manager\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class EntityManagerFactoryTest extends TestCase
{
    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeCreatedEntityManager(): void
    {
        $app = new App();
        $factory = new EntityManagerFactory($app->getEntityClassNamespace());
        $repository = $app->getRepository();
        $entity = $factory->create($app->getEntityName(), $repository);

        $this->assertInstanceOf(EntityManagerInterface::class, $entity);
    }
}
