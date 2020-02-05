<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Service\Entity\EntityServiceInterface;

interface EntityServiceFactoryInterface
{
    public function __construct(EntityFactoryInterface $entityFactory);

    public function create(): EntityServiceInterface;
}
