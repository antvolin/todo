<?php

namespace Tests\Lib\Service\Pdo;

use Todo\Lib\App;
use PDO;
use PHPUnit\Framework\TestCase;
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

    protected function setUp()
    {
        $app = new App();
        $this->pdoService = new PdoService($app->getEntityName(), $app->getStorageType(), $app->getDbFolderName());
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
