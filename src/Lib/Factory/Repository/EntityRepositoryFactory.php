<?php

namespace BeeJeeMVC\Lib\Factory\Repository;

use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use BeeJeeMVC\Model\EntityInterface;

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
