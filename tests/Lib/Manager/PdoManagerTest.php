<?php

namespace BeeJeeMVC\Tests\Lib\Manager;

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
        $this->pdoManager = new PdoManager($_ENV['ENTITY_NAME'], $_ENV['STORAGE_TYPE'], $_ENV['DB_FOLDER_NAME']);
        $this->pdo = $this->pdoManager->getPdo();
    }

    /**
     * @test
     */
    public function shouldBeGettingPdo(): void
    {
        $this->assertInstanceOf(Pdo::class, $this->pdo);
    }

    /**
     * @test
     */
    public function shouldBeCreatedTables(): void
    {
        $this->assertTrue($this->pdoManager->createTables());
    }
}
