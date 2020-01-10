<?php

namespace BeeJeeMVC\Lib\Factory\Manager;

use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Manager\EntityManager;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use BeeJeeMVC\Model\EntityInterface;

class EntityManagerFactory
{
    /**
     * @var string
     */
    private $entityFolderNamespace;

    /**
     * @param string $entityFolderNamespace
     */
    public function __construct(string $entityFolderNamespace)
    {
        $this->entityFolderNamespace = $entityFolderNamespace;
    }

    /**
     * @param string $entityName
     * @param EntityRepositoryInterface $repository
     *
     * @return EntityManager
     *
     * @throws NotAllowedEntityName
     * @throws NotAllowedEntityName
     */
    public function create(string $entityName, EntityRepositoryInterface $repository): EntityManager
    {
        if (!in_array($entityName, EntityInterface::ALLOWED_ENTITY_NAMES, true)) {
            throw new NotAllowedEntityName();
        }

        return new EntityManager($this->entityFolderNamespace.ucfirst(strtolower($entityName)), $repository);
    }
}
