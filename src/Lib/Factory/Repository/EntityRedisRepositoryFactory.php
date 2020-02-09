<?php

namespace Todo\Lib\Factory\Repository;

use Redis;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Repository\EntityRedisRepository;
use Todo\Lib\Repository\EntityRepositoryInterface;

class EntityRedisRepositoryFactory implements EntityRepositoryFactoryInterface
{
    private Redis $redis;
    private EntityFactoryInterface $entityFactory;
    private string $entityPerPage;

    public function __construct(
        Redis $redis,
        EntityFactoryInterface $entityFactory,
        string $entityPerPage
    )
    {
        $this->redis = $redis;
        $this->entityFactory = $entityFactory;
        $this->entityPerPage = $entityPerPage;
    }

    public function createRepository(): EntityRepositoryInterface
    {
        return new EntityRedisRepository(
            $this->redis,
            $this->entityFactory,
            $this->entityPerPage
        );
    }
}
