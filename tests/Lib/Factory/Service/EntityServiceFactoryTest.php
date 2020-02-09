<?php

namespace Tests\Lib\Factory\Service;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Factory\Service\EntityServiceFactory;
use Todo\Lib\Service\Entity\EntityService;

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
        $entityService = $factory->createService();

        $this->assertInstanceOf(EntityService::class, $entityService);
    }
}
