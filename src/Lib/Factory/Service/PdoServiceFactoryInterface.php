<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Service\Pdo\PdoServiceInterface;

interface PdoServiceFactoryInterface
{
    /**
     * @param string $entityName
     * @param string $pdoType
     * @param string $dbFolderName
     */
    public function __construct(string $entityName, string $pdoType, string $dbFolderName);
    /**
     * @return PdoServiceInterface
     */
    public function create(): PdoServiceInterface;
}
