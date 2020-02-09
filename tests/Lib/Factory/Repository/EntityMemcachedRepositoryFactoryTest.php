<?php

namespace Tests\Lib\Factory\Repository;

use Memcached;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Factory\Repository\EntityMemcachedRepositoryFactory;
use Todo\Lib\Repository\EntityMemcachedRepository;

class EntityMemcachedRepositoryFactoryTest extends TestCase
{
    private int $entityPerPage;
    private MockObject $memcached;

    protected function setUp()
    {
        $this->entityPerPage = 3;
        $this->memcached = $this->createMock(Memcached::class);
    }

    /**
     * @test
     */
    public function shouldBeCreatableEntityPdoRepository(): void
    {
        $entityFactory = $this->createMock(EntityFactoryInterface::class);
        $repositoryFactory = new EntityMemcachedRepositoryFactory(
            $this->memcached,
            $entityFactory,
            $this->entityPerPage,
            App::getMemcachedServer()
        );

        $repository = $repositoryFactory->createRepository();

        $this->assertInstanceOf(EntityMemcachedRepository::class, $repository);
    }
}
