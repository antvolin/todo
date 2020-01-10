<?php

namespace BeeJeeMVC\Lib\Factory\Repository;

use BeeJeeMVC\Lib\Repository\EntityFileRepository;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;

class EntityFileRepositoryFactory extends EntityRepositoryFactory
{
    /**
     * @inheritdoc
     */
    public function create(int $entityPerPage): EntityRepositoryInterface
    {
        return new EntityFileRepository(dirname(__DIR__) . '/src/' .$this->entityName, $entityPerPage);
    }
}
