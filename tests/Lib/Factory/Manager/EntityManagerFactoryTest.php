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
    public function shouldBeCreatedValidEntityManager(): void
    {
        $entity = (new EntityManagerFactory)->create($_ENV['ENTITY_NAME'], (new App())->getRepository());
        $this->assertInstanceOf(EntityManagerInterface::class, $entity);
    }
}
