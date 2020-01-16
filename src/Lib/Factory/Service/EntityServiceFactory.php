<?php

namespace BeeJeeMVC\Lib\Factory\Service;

use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Service\EntityService;
use BeeJeeMVC\Lib\Service\EntityServiceInterface;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use BeeJeeMVC\Model\EntityInterface;

class EntityServiceFactory implements EntityServiceFactoryInterface
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
