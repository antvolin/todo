<?php

namespace Todo\Lib\Factory\Repository;

use Todo\Lib\Repository\EntityRepositoryInterface;

interface EntityRepositoryFactoryInterface
{
    /**
     * @param int $entityPerPage
     * @param string $entityName
     *
     * @return EntityRepositoryInterface
     */
    public function create(int $entityPerPage, string $entityName): EntityRepositoryInterface;
}
