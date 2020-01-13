<?php

namespace BeeJeeMVC\Tests\Lib\Manager;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Manager\PdoManager;
use PDO;
use PHPUnit\Framework\TestCase;

class PdoManagerTest extends TestCase
{
    /**
     * PdoManager $pdoManager
     */
    protected $pdoManager;

    /**
     * Pdo $pdo
     */
    protected $pdo;

    protected function setUp()
    {
        $app = new App();
        $this->pdoManager = new PdoManager($app->getEntityName(), $app->getStorageType(), $app->getDbFolderName());
        $this->pdo = $this->pdoManager->getPdo();
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
        $this->assertTrue($this->pdoManager->createTables());
    }
}
