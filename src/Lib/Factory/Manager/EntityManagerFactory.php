<?php

namespace BeeJeeMVC\Lib\Factory\Manager;

use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Manager\EntityService;
use BeeJeeMVC\Lib\Manager\EntityServiceInterface;
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
     * @return EntityServiceInterface
     *
     * @throws NotAllowedEntityName
     * @throws NotAllowedEntityName
     */
    public function create(string $entityName, EntityRepositoryInterface $repository): EntityServiceInterface
    {
        if (!in_array($entityName, EntityInterface::ALLOWED_ENTITY_NAMES, true)) {
            throw new NotAllowedEntityName();
        }

        return new EntityService($this->entityClassNamespace.ucfirst(strtolower($entityName)), $repository);
    }
}
