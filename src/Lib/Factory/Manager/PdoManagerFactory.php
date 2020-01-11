<?php

namespace BeeJeeMVC\Lib\Factory\Manager;

use BeeJeeMVC\Lib\Manager\PdoManager;
use BeeJeeMVC\Lib\Manager\PdoManagerInterface;

class PdoManagerFactory
{
    /**
     * @var string
     */
    private $entityName;

    /**
     * @var string
     */
    private $pdoType;

    /**
     * @var string
     */
    private $dbFolderName;

    /**
     * @param string $entityName
     * @param string $pdoType
     * @param string $dbFolderName
     */
    public function __construct(string $entityName, string $pdoType, string $dbFolderName)
    {
        $this->entityName = strtolower($entityName);
        $this->pdoType = strtolower($pdoType);
        $this->dbFolderName = strtolower($dbFolderName);
    }

    /**
     * @return PdoManagerInterface
     */
    public function create(): PdoManagerInterface
    {
        return new PdoManager($this->entityName, $this->pdoType, $this->dbFolderName);
    }
}
