<?php

namespace Todo\Lib\Factory\Repository;

use Todo\Lib\Repository\EntityFileRepository;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Lib\Service\Path\PathService;

class EntityFileRepositoryFactory implements EntityRepositoryFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function create(int $entityPerPage, string $entityName): EntityRepositoryInterface
    {
        $entityStoragePath = PathService::getPathToEntityStorage($entityName, 3);

        if (!is_dir($entityStoragePath) && !mkdir($entityStoragePath) && !is_dir($entityStoragePath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $entityStoragePath));
        }

        return new EntityFileRepository($entityStoragePath, $entityPerPage);
    }
}
