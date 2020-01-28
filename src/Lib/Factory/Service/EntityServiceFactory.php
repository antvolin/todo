<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Service\Entity\EntityService;
use Todo\Lib\Service\Entity\EntityServiceInterface;

class EntityServiceFactory implements EntityServiceFactoryInterface
{
    /**
     * @var EntityFactoryInterface
     */
    private $entityFactory;

    /**
     * @param EntityFactoryInterface $entityFactory
     */
    public function __construct(EntityFactoryInterface $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    /**
     * @return EntityServiceInterface
     */
    public function create(): EntityServiceInterface
    {
        return new EntityService($this->entityFactory);
    }
}
