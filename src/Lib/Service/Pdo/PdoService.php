<?php

namespace Todo\Lib\Service\Pdo;

use PDO;
use PDOException;
use Todo\Lib\DB\PdoDatabaseConnection;
use Todo\Lib\Exceptions\PdoConnectionException;

class PdoService implements PdoServiceInterface
{
    private PDO $pdo;
    private PdoDatabaseConnection $connection;

    public function __construct(PdoDatabaseConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return PDO
     *
     * @throws PdoConnectionException
     */
    public function getPdo(): PDO
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
}
