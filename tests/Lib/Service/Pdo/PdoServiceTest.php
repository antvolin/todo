<?php

namespace Tests\Lib\Service\Pdo;

use Todo\Lib\App;
use PDO;
use PHPUnit\Framework\TestCase;
use Todo\Lib\Exceptions\PdoConnectionException;
use Todo\Lib\Service\Pdo\PdoService;

class PdoServiceTest extends TestCase
{
    /**
     * PdoService $pdoService
     */
    protected $pdoService;

    /**
     * Pdo $pdo
     */
    protected $pdo;

    /**
     * @throws PdoConnectionException
     */
    protected function setUp()
    {
        $this->pdoService = new PdoService(App::getEntityName(), App::getStorageType(), App::getDbFolderName());
        $this->pdo = $this->pdoService->getPdo();
    }

    /**
     * @test
     */
    public function shouldBeGettingPdo(): void
    {
        $this->assertInstanceOf(PDO::class, $this->pdo);
    }

    /**
     * @test
     */
    public function shouldBeCreatedTables(): void
    {
        $this->assertTrue($this->pdoService->createTables());
    }
}
