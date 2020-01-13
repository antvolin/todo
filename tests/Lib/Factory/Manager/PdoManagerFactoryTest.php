<?php

namespace BeeJeeMVC\Tests\Lib\Factory\Manager;

use BeeJeeMVC\Lib\App;
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
        $app = new App();
        $factory = new PdoManagerFactory(
            $app->getEntityName(),
            $app->getStorageType(),
            $app->getDbFolderName()
        );
        $pdoManager = $factory->create();

        $this->assertInstanceOf(PdoManagerInterface::class, $pdoManager);
    }
}
