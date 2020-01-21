<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Service\EntityServiceInterface;
use Todo\Lib\Repository\EntityRepositoryInterface;

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
