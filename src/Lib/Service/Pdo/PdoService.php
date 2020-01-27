<?php

namespace Todo\Lib\Service\Pdo;

use PDO;
use PDOException;
use Todo\Lib\Exceptions\PdoConnectionException;
use Todo\Lib\Service\Path\PathService;

class PdoService implements PdoServiceInterface
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var string
     */
    private $pdoType;

    /**
     * @var string
     */
    private $dbFolderName;

    /**
     * @inheritDoc
     */
    public function __construct(string $entityName, string $pdoType, string $dbFolderName)
    {
        $this->entityName = $entityName;
        $this->pdoType = $pdoType;
        $this->dbFolderName = $dbFolderName;
    }

    /**
     * @inheritDoc
     */
    public function getPdo(): PDO
    {
        $dsn = PathService::getPathToPdoDsn($this->pdoType, $this->dbFolderName, $this->entityName);

        try {
            $this->pdo = new PDO($dsn);
        } catch (PDOException $exception) {
            throw new PdoConnectionException($exception->getMessage().' with dsn '.$dsn);
        }

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->pdo;
    }

    /**
     * @inheritDoc
     */
    public function createTables(): bool
    {
        $result1 = $this->pdo->query(sprintf('CREATE TABLE IF NOT EXISTS %s (id INTEGER PRIMARY KEY, user_name TEXT, email TEXT, text TEXT, status TEXT);', $this->entityName));
        $result2 = $this->pdo->query(sprintf('CREATE UNIQUE INDEX IF NOT EXISTS idx_%s_user_name_email_text ON %s (user_name, email, text);', $this->entityName, $this->entityName));

        return $result1 && $result2;
    }
}