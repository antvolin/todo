<?php

namespace BeeJeeMVC\Lib\Factory\Service;

use BeeJeeMVC\Lib\Service\PdoService;
use BeeJeeMVC\Lib\Service\PdoServiceInterface;

class PdoServiceFactory implements PdoServiceFactoryInterface
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
     * @return PdoServiceInterface
     */
    public function create(): PdoServiceInterface
    {
        return new PdoService($this->entityName, $this->pdoType, $this->dbFolderName);
    }
}
