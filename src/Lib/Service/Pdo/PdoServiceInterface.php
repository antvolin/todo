<?php

namespace Todo\Lib\Service\Pdo;

use PDO;
use Todo\Lib\DB\PdoDatabaseConnection;

interface PdoServiceInterface
{
    public function __construct(PdoDatabaseConnection $connection);

    public function getPdo(): PDO;
}
