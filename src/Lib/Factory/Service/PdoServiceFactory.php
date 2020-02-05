<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\DB\PdoDatabaseConfiguration;
use Todo\Lib\DB\PdoDatabaseConnection;
use Todo\Lib\Service\Pdo\PdoService;
use Todo\Lib\Service\Pdo\PdoServiceInterface;

class PdoServiceFactory implements PdoServiceFactoryInterface
{
    private string $entityName;
    private string $dbType;
    private string $dbFolderName;

    public function __construct(string $entityName, string $dbType, string $dbFolderName)
    {
        $this->entityName = strtolower($entityName);
        $this->dbType = strtolower($dbType);
        $this->dbFolderName = strtolower($dbFolderName);
    }

    public function create(): PdoServiceInterface
    {
        $pdoDatabaseConfiguration = new PdoDatabaseConfiguration($this->entityName, $this->dbType, $this->dbFolderName);
        $pdoDatabaseConnection = new PdoDatabaseConnection($pdoDatabaseConfiguration);

        return new PdoService($pdoDatabaseConnection);
    }
}
