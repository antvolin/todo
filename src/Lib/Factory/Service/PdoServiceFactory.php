<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Service\Pdo\PdoService;
use Todo\Lib\Service\Pdo\PdoServiceInterface;

class PdoServiceFactory implements PdoServiceFactoryInterface
{
    private string $entityName;
    private string $pdoType;
    private string $dbFolderName;

    /**
     * @inheritDoc
     */
    public function __construct(string $entityName, string $pdoType, string $dbFolderName)
    {
        $this->entityName = strtolower($entityName);
        $this->pdoType = strtolower($pdoType);
        $this->dbFolderName = strtolower($dbFolderName);
    }

    /**
     * @inheritDoc
     */
    public function create(): PdoServiceInterface
    {
        return new PdoService($this->entityName, $this->pdoType, $this->dbFolderName);
    }
}
