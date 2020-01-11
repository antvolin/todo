<?php

namespace BeeJeeMVC\Lib\Factory\Repository;

use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Repository\EntityPdoRepository;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use PDO;

class EntityPdoRepositoryFactory extends EntityRepositoryFactory
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $entityFolderNamespace;

    /**
     * @param PDO $pdo
     * @param string $entityName
     * @param string $entityFolderNamespace
     * @throws NotAllowedEntityName
     */
    public function __construct(Pdo $pdo, string $entityName, string $entityFolderNamespace)
    {
        parent::__construct($entityName);

        $this->pdo = $pdo;
        $this->entityFolderNamespace = $entityFolderNamespace;
    }

    /**
     * @inheritdoc
     */
    public function create(int $entityPerPage): EntityRepositoryInterface
    {
        return new EntityPdoRepository($this->pdo, $this->entityName, $entityPerPage, $this->entityFolderNamespace);
    }
}