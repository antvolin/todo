<?php

namespace BeeJeeMVC\Lib\Factory\Manager;

use BeeJeeMVC\Lib\Manager\PdoServiceInterface;

interface PdoManagerFactoryInterface
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
