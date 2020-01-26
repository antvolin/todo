<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Service\Entity\EntityService;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Model\EntityInterface;

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
     *
     * @return EntityServiceInterface
     *
     * @throws NotAllowedEntityName
     * @throws NotAllowedEntityName
     */
    public function create(string $entityName): EntityServiceInterface
    {
        if (!in_array($entityName, EntityInterface::ALLOWED_ENTITY_NAMES, true)) {
            throw new NotAllowedEntityName();
        }

        return new EntityService($this->entityClassNamespace, $entityName);
    }
}
