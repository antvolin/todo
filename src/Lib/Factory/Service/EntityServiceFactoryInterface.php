<?php

namespace BeeJeeMVC\Lib\Factory\Service;

use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Service\EntityServiceInterface;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;

interface EntityServiceFactoryInterface
{
    /**
     * @param string $entityClassNamespace
     */
    public function __construct(string $entityClassNamespace);

    /**
     * @param string $entityName
     * @param EntityRepositoryInterface $repository
     *
     * @return EntityServiceInterface
     *
     * @throws NotAllowedEntityName
     * @throws NotAllowedEntityName
     */
    public function create(string $entityName, EntityRepositoryInterface $repository): EntityServiceInterface;
}
