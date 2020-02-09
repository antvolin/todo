<?php

namespace Todo\Lib\Factory\Repository;

use Memcached;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Repository\EntityMemcachedRepository;
use Todo\Lib\Repository\EntityRepositoryInterface;

class EntityMemcachedRepositoryFactory implements EntityRepositoryFactoryInterface
{
    private Memcached $memcached;
    private EntityFactoryInterface $entityFactory;
    private string $entityPerPage;
    private string $server;

    public function __construct(
        Memcached $memcached,
        EntityFactoryInterface $entityFactory,
        string $entityPerPage,
        string $server
    )
    {
        $this->memcached = $memcached;
        $this->entityFactory = $entityFactory;
        $this->entityPerPage = $entityPerPage;
        $this->server = $server;
    }

    public function createRepository(): EntityRepositoryInterface
    {
        return new EntityMemcachedRepository(
            $this->memcached,
            $this->entityFactory,
            $this->entityPerPage,
            $this->server
        );
    }
}
