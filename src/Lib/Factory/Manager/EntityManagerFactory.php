<?php

namespace BeeJeeMVC\Lib\Factory\Manager;

use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Manager\EntityManager;
use BeeJeeMVC\Lib\Manager\EntityManagerInterface;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use BeeJeeMVC\Model\EntityInterface;

class EntityManagerFactory implements EntityManagerFactoryInterface
{
    /**
     * @var string
     */
    private $entityClassNamespace;

    /**
     * @param string $entityClassNamespace
     */
    public function __construct(string $entityClassNamespace)
    {
        $this->entityClassNamespace = $entityClassNamespace;
    }

    /**
     * @param string $entityName
     * @param EntityRepositoryInterface $repository
     *
     * @return EntityManagerInterface
     *
     * @throws NotAllowedEntityName
     * @throws NotAllowedEntityName
     */
    public function create(string $entityName, EntityRepositoryInterface $repository): EntityManagerInterface
    {
        if (!in_array($entityName, EntityInterface::ALLOWED_ENTITY_NAMES, true)) {
            throw new NotAllowedEntityName();
        }

        return new EntityManager($this->entityClassNamespace.ucfirst(strtolower($entityName)), $repository);
    }
}
