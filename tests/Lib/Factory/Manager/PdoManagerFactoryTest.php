<?php

namespace BeeJeeMVC\Tests\Lib\Factory\Manager;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Factory\Manager\PdoManagerFactory;
use PHPUnit\Framework\TestCase;

class PdoManagerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedPdoManager(): void
    {
        $app = new App();
        $factory = new PdoManagerFactory(
            $app->getEntityName(),
            $app->getStorageType(),
            $app->getDbFolderName()
        );
        $pdoManager = $factory->create();

        $this->assertTrue(method_exists($pdoManager, 'createTables'));
        $this->assertTrue(method_exists($pdoManager, 'getPdo'));
    }
}
