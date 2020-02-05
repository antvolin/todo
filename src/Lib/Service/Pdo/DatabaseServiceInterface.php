<?php

namespace Todo\Lib\Service\Pdo;

interface DatabaseServiceInterface
{
    public function createTables(): bool;
}
