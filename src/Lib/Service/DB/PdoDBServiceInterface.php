<?php

namespace Todo\Lib\Service\DB;

interface PdoDBServiceInterface
{
    public function createTables(): bool;
}
