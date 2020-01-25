<?php

namespace Todo\Lib\Factory\Repository;

use Todo\Lib\Repository\EntityFileRepository;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Lib\Service\Path\PathService;

class EntityFileRepositoryFactory extends EntityRepositoryFactory
{
    /**
     * @inheritdoc
     */
    public function create(int $entityPerPage): EntityRepositoryInterface
    {
        $entityStoragePath = PathService::getSrcPathByLevel() .$this->entityName;

        return new EntityFileRepository($entityStoragePath, $entityPerPage);
    }
}
