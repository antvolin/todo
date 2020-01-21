<?php

namespace Todo\Lib\Factory\Repository;

use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Model\EntityInterface;

abstract class EntityRepositoryFactory
{
    /**
     * @var string
     */
    protected $entityName;

    /**
     * @param string $entityName
     *
     * @throws NotAllowedEntityName
     */
    public function __construct(string $entityName)
    {
        if (!in_array($entityName, EntityInterface::ALLOWED_ENTITY_NAMES, true)) {
            throw new NotAllowedEntityName();
        }

        $this->entityName = $entityName;
    }

    /**
     * @param int $entityPerPage
     *
     * @return EntityRepositoryInterface
     */
    abstract public function create(int $entityPerPage): EntityRepositoryInterface;
}
