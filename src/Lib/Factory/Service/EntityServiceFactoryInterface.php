<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Service\Entity\EntityServiceInterface;

interface EntityServiceFactoryInterface
{
    /**
     * @param string $entityClassNamespace
     */
    public function __construct(string $entityClassNamespace);

    /**
     * @param string $entityName
     *
     * @return EntityServiceInterface
     *
     * @throws NotAllowedEntityName
     * @throws NotAllowedEntityName
     */
    public function create(string $entityName): EntityServiceInterface;
}
