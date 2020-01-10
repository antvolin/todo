<?php

namespace BeeJeeMVC\Lib\Factory\Manager;

use BeeJeeMVC\Lib\Manager\PdoManager;

class PdoManagerFactory
{
    /**
     * @var string
     */
    private $pdoType;

    /**
     * @var string
     */
    private $dbFolderName;


    /**
     * @var string
     */
    private $entityName;

    /**
     * @param string $pdoType
     * @param string $dbFolderName
     * @param string $entityName
     */
    public function __construct(string $pdoType, string $dbFolderName, string $entityName)
    {
        $this->pdoType = strtolower($pdoType);
        $this->dbFolderName = strtolower($dbFolderName);
        $this->entityName = strtolower($entityName);
    }

    /**
     * @return PdoManager
     */
    public function create(): PdoManager
    {
        return new PdoManager($this->pdoType, $this->dbFolderName, $this->entityName);
    }
}
