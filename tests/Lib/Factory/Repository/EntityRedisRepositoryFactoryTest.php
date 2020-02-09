<?php

namespace Tests\Lib\Factory\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Factory\Repository\EntityRedisRepositoryFactory;
use Todo\Lib\Repository\EntityRedisRepository;

class EntityRedisRepositoryFactoryTest extends TestCase
{
    private int $entityPerPage;
    private MockObject $redis;

    protected function setUp()
    {
        $this->entityPerPage = 3;
        $this->redis = $this->createMock(\Redis::class);
    }

    /**
     * @test
     */
    public function shouldBeCreatableEntityPdoRepository(): void
    {
        $entityFactory = $this->createMock(EntityFactoryInterface::class);
        $repositoryFactory = new EntityRedisRepositoryFactory(
            $this->redis,
            $entityFactory,
            $this->entityPerPage
        );

        $repository = $repositoryFactory->createRepository();

        $this->assertInstanceOf(EntityRedisRepository::class, $repository);
    }
}
