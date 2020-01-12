<?php

namespace BeeJeeMVC\Lib\Factory\Manager;

use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Manager\EntityManagerInterface;
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
     * @return EntityManagerInterface
     *
     * @throws NotAllowedEntityName
     * @throws NotAllowedEntityName
     */
    public function create(string $entityName, EntityRepositoryInterface $repository): EntityManagerInterface;
}
