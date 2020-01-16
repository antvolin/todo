<?php

namespace BeeJeeMVC\Lib\Factory\Service;

use BeeJeeMVC\Lib\Service\PdoServiceInterface;

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
