<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Service\Pdo\PdoService;
use Todo\Lib\Service\Pdo\PdoServiceInterface;

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
