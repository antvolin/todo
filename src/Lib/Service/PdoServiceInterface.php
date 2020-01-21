<?php

namespace Todo\Lib\Service;

use PDO;

interface PdoServiceInterface
{
    /**
     * @param string $pdoType
     * @param string $dbFolderName
     * @param string $entityName
     */
    public function __construct(string $pdoType, string $dbFolderName, string $entityName);

    /**
     * @return PDO
     */
    public function getPdo(): PDO;

    /**
     * @return bool
     */
    public function createTables(): bool;
}
