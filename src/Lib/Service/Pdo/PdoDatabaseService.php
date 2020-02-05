<?php

namespace Todo\Lib\Service\Pdo;

use PDO;

class PdoDatabaseService implements DatabaseServiceInterface
{
    private PDO $pdo;
    private string $dbName;

    public function __construct(PDO $pdo, string $dbName)
    {
        $this->pdo = $pdo;
        $this->dbName = $dbName;
    }

    public function createTables(): bool
    {
        $isCreated1 = $this->pdo->query(
            sprintf('
                CREATE TABLE IF NOT EXISTS %s
                (id INTEGER PRIMARY KEY, user_name TEXT, email TEXT, text TEXT, status TEXT);
            ', $this->dbName)
        );
        $isCreated2 = $this->pdo->query(
            sprintf('
                CREATE UNIQUE INDEX IF NOT EXISTS idx_%s_user_name_email_text ON %s
                    (user_name, email, text);
            ', $this->dbName, $this->dbName)
        );

        return $isCreated1 && $isCreated2;
    }
}
