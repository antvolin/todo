<?php

namespace BeeJeeMVC\Lib\Factory\Repository;

use BeeJeeMVC\Lib\Manager\PathManager;
use BeeJeeMVC\Lib\Repository\EntityFileRepository;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;

class EntityFileRepositoryFactory extends EntityRepositoryFactory
{
    /**
     * @inheritdoc
     */
    public function create(int $entityPerPage): EntityRepositoryInterface
    {
        $entityStoragePath = PathManager::getSrcPathByLevel() .$this->entityName;

        return new EntityFileRepository($entityStoragePath, $entityPerPage);
    }
}
