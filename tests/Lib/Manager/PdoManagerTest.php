<?php

namespace BeeJeeMVC\Tests\Lib\Manager;

use BeeJeeMVC\Lib\Manager\PdoManager;
use PHPUnit\Framework\TestCase;

class PdoManagerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeGettingPdo(): void
    {
        $entityName = $_ENV['ENTITY_NAME'];

        $pdoManager = new PdoManager($entityName, $_ENV['STORAGE_TYPE'], $_ENV['DB_FOLDER_NAME']);
        $pdo = $pdoManager->getPdo();

        $this->assertIsObject($pdo);
    }

    /**
     * @test
     */
    public function shouldBeCreatedTables(): void
    {
        $this->markTestIncomplete();
    }
}
