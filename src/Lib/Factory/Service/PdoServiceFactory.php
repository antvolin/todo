<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Service\DB\PdoDBConfiguration;
use Todo\Lib\Service\DB\PdoDBConnection;
use Todo\Lib\Service\DB\PdoDBService;

class PdoServiceFactory implements ServiceFactoryInterface
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

    public function createService(): PdoDBService
    {
        $pdoDatabaseConfiguration = new PdoDBConfiguration($this->entityName, $this->dbType, $this->dbFolderName);
        $pdoDatabaseConnection = new PdoDBConnection($pdoDatabaseConfiguration);

        return new PdoDBService($pdoDatabaseConnection, $this->entityName);
    }
}
