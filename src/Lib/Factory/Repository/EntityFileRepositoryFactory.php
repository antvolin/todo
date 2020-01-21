<?php

namespace Todo\Lib\Factory\Repository;

use Todo\Lib\Service\PathService;
use Todo\Lib\Repository\EntityFileRepository;
use Todo\Lib\Repository\EntityRepositoryInterface;

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
