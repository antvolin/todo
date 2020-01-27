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
    private $pdoService;

    protected function setUp()
    {
        $this->pdoService = new PdoService(App::getEntityName(), App::getStorageType(), App::getDbFolderName());
    }

    /**
     * @test
     *
     * @throws PdoConnectionException
     */
    public function shouldBeGettingPdo(): void
    {
        $this->assertInstanceOf(PDO::class, $this->pdoService->getPdo());
    }

    /**
     * @test
     */
    public function shouldBeCreatedTables(): void
    {
        $this->pdoService->getPdo();
        $this->assertTrue($this->pdoService->createTables());
    }
}
