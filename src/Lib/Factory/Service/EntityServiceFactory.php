<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Service\Entity\EntityService;
use Todo\Lib\Service\Entity\EntityServiceInterface;

class EntityServiceFactory implements ServiceFactoryInterface
{
    private EntityFactoryInterface $entityFactory;

    public function __construct(EntityFactoryInterface $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    public function createService(): EntityServiceInterface
    {
        return new EntityService($this->entityFactory);
    }
}
