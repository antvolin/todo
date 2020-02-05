<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Service\Pdo\PdoServiceInterface;

interface PdoServiceFactoryInterface
{
    public function __construct(string $entityName, string $dbType, string $dbFolderName);

    public function create(): PdoServiceInterface;
}
