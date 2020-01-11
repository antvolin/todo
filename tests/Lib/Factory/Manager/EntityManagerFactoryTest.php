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
        $factory = new EntityManagerFactory($_ENV['ENTITY_FOLDER_NAMESPACE']);
        $repository = (new App())->getRepository();
        $entity = $factory->create($_ENV['ENTITY_NAME'], $repository);

        $this->assertInstanceOf(EntityManagerInterface::class, $entity);
    }
}
