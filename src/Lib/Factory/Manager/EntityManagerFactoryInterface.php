<?php

namespace BeeJeeMVC\Lib\Factory\Manager;

use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Manager\EntityServiceInterface;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;

interface EntityManagerFactoryInterface
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
