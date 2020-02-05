<?php

namespace Todo\Lib\DB;

interface DatabaseConfigurationInterface
{
    public function __construct(string $dbType, string $dbFolderName, string $entityName);

    public function getDbType(): string;

    public function getEntityName(): string;

    public function getDbFolderName(): string;
}
