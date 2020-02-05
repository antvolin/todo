<?php

namespace Todo\Lib\DB;

class PdoDatabaseConfiguration implements DatabaseConfigurationInterface
{
    private string $entityName;
    private string $dbType;
    private string $dbFolderName;

    public function __construct(string $entityName, string $dbType, string $dbFolderName)
    {
        $this->entityName = $entityName;
        $this->dbType = $dbType;
        $this->dbFolderName = $dbFolderName;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getDbType(): string
    {
        return $this->dbType;
    }

    public function getDbFolderName(): string
    {
        return $this->dbFolderName;
    }
}
