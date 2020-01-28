<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Service\Entity\EntityServiceInterface;

interface EntityServiceFactoryInterface
{
    /**
     * @param EntityFactoryInterface $entityFactory
     */
    public function __construct(EntityFactoryInterface $entityFactory);

    /**
     * @return EntityServiceInterface
     */
    public function create(): EntityServiceInterface;
}
