<?php

namespace Todo\Lib\Factory\Repository;

use Todo\Lib\Repository\EntityRepositoryInterface;

interface EntityRepositoryFactoryInterface
{
    public function createRepository(): EntityRepositoryInterface;
}
