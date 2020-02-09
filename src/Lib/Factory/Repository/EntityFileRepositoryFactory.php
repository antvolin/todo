<?php

namespace Todo\Lib\Factory\Repository;

use Todo\Lib\Exceptions\CannotCreateDirectoryException;
use Todo\Lib\Repository\EntityFileRepository;
use Todo\Lib\Repository\EntityRepositoryInterface;
use Todo\Lib\Service\Path\PathService;

class EntityFileRepositoryFactory implements EntityRepositoryFactoryInterface
{
    private string $entityPerPage;
    private string $entityName;

    public function __construct(string $entityPerPage, string $entityName)
    {
        $this->entityPerPage = $entityPerPage;
        $this->entityName = $entityName;
    }
    /**
     * @return EntityRepositoryInterface
     *
     * @throws CannotCreateDirectoryException
     */
    public function createRepository(): EntityRepositoryInterface
    {
        $entityStoragePath = PathService::getPathToEntityStorage($this->entityName, 3);

        if (!is_dir($entityStoragePath) && !mkdir($entityStoragePath) && !is_dir($entityStoragePath)) {
            throw new CannotCreateDirectoryException(sprintf('Directory "%s" was not created', $entityStoragePath));
        }

        return new EntityFileRepository($entityStoragePath, $this->entityPerPage);
    }
}
