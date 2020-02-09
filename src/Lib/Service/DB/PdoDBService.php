<?php

namespace Todo\Lib\Service\DB;

use PDO;
use PDOException;
use Todo\Lib\Exceptions\PdoConnectionException;

class PdoDBService implements DBServiceInterface, PdoDBServiceInterface
{
    private ?Pdo $pdo = null;
    private PdoDBConnection $connection;
    private string $dbName;

    public function __construct(PdoDBConnection $connection, string $dbName)
    {
        $this->connection = $connection;
        $this->dbName = $dbName;
    }

    /**
     * @return PDO
     *
     * @throws PdoConnectionException
     */
    public function getDBInstance(): PDO
    {
        $dsn = $this->connection->getDsn();

        try {
            $this->pdo = new PDO($dsn);
        } catch (PDOException $exception) {
            throw new PdoConnectionException(sprintf('%s with dsn %s', $exception->getMessage(), $dsn));
        }

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->pdo;
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
