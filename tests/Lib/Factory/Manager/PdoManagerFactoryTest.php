<?php

namespace BeeJeeMVC\Tests\Lib\Factory\Manager;

use BeeJeeMVC\Lib\Factory\Manager\PdoManagerFactory;
use BeeJeeMVC\Lib\Manager\PdoManagerInterface;
use PHPUnit\Framework\TestCase;

class PdoManagerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedPdoManager(): void
    {
        $factory = new PdoManagerFactory(
            $_ENV['ENTITY_NAME'],
            $_ENV['STORAGE_TYPE'],
            $_ENV['DB_FOLDER_NAME']
        );
        $pdoManager = $factory->create();

        $this->assertInstanceOf(PdoManagerInterface::class, $pdoManager);
    }
}
