<?php

namespace Todo\Lib\Factory\Repository;

use Todo\Lib\Repository\EntityFileRepository;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Lib\Service\Path\PathService;

class EntityFileRepositoryFactoryInterface implements EntityRepositoryFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function create(int $entityPerPage, string $entityName): EntityRepositoryInterface
    {
        $entityStoragePath = PathService::getSrcPathByLevel().$entityName;

        return new EntityFileRepository($entityStoragePath, $entityPerPage);
    }
}
