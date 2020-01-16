<?php

namespace BeeJeeMVC\Lib\Factory\Repository;

use BeeJeeMVC\Lib\Service\PathService;
use BeeJeeMVC\Lib\Repository\EntityFileRepository;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;

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
