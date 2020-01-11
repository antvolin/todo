<?php

namespace BeeJeeMVC\Lib\Manager;

use PDO;

class PdoManager implements PdoManagerInterface
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
        $this->connect();
//         $this->createTables();

        return $this->pdo;
    }

    public function connect(): void
    {
        $this->pdo = new PDO($this->pdoType.':'.dirname(__DIR__).'/../..'.$this->dbFolderName.$this->entityName);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function createTables(): void
    {
        $this->pdo->exec(sprintf('CREATE TABLE IF NOT EXISTS %s (id INTEGER PRIMARY KEY, user_name TEXT, email TEXT, text TEXT, status TEXT);', $this->entityName));
        $this->pdo->exec(sprintf('CREATE UNIQUE INDEX idx_%s_user_name_email_text ON %s (user_name, email, text);', $this->entityName, $this->entityName));
    }
}