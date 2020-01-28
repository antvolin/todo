<?php

namespace Todo\Lib\Factory\Repository;

use Todo\Lib\Repository\EntityRepositoryInterface;

abstract class EntityRepositoryFactory
{
    /**
     * @param int $entityPerPage
     * @param string $entityName
     *
     * @return EntityRepositoryInterface
     */
    abstract public function create(int $entityPerPage, string $entityName): EntityRepositoryInterface;
}
