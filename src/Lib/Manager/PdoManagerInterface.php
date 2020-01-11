<?php

namespace BeeJeeMVC\Lib\Manager;

use PDO;

interface PdoManagerInterface
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

    public function connect(): void;

    public function createTables(): void;
}