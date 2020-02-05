<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Service\Entity\EntityService;
use Todo\Lib\Service\Entity\EntityServiceInterface;

class EntityServiceFactory implements EntityServiceFactoryInterface
{
    private EntityFactoryInterface $entityFactory;

    public function __construct(EntityFactoryInterface $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    public function create(): EntityServiceInterface
    {
        return new EntityService($this->entityFactory);
    }
}
