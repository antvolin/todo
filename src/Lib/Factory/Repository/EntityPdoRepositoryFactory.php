<?php

namespace BeeJeeMVC\Lib\Factory\Repository;

use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Repository\EntityPdoRepository;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;

class EntityPdoRepositoryFactory extends EntityRepositoryFactory
{
    /**
     * @var string
     */
    private $pdoType;

    /**
     * @var string
     */
    private $dbFolderName;


    /**
     * @var string
     */
    private $entityFolderNamespace;

    /**
     * @param string $entityName
     * @param string $pdoType
     * @param string $dbFolderName
     *
     * @param string $entityFolderNamespace
     * @throws NotAllowedEntityName
     */
    public function __construct(string $entityName, string $pdoType, string $dbFolderName, string $entityFolderNamespace)
    {
        parent::__construct($entityName);

        $this->pdoType = strtolower($pdoType);
        $this->dbFolderName = strtolower($dbFolderName);
        $this->entityFolderNamespace = $entityFolderNamespace;
    }

    /**
     * @inheritdoc
     */
    public function create(int $entityPerPage): EntityRepositoryInterface
    {
        return new EntityPdoRepository($this->entityName, $entityPerPage, $this->pdoType, $this->dbFolderName, $this->entityFolderNamespace);
    }
}
