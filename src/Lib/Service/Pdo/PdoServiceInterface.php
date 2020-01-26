<?php

namespace Todo\Lib\Service\Pdo;

use PDO;
use Todo\Lib\Exceptions\PdoConnectionException;

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
     *
     * @throws PdoConnectionException
     */
    public function getPdo(): PDO;

    /**
     * @return bool
     */
    public function createTables(): bool;
}
