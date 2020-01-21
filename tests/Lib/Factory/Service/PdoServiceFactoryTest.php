<?php

namespace Todo\Tests\Lib\Factory\Service;

use Todo\Lib\App;
use Todo\Lib\Factory\Service\PdoServiceFactory;
use PHPUnit\Framework\TestCase;

class PdoServiceFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedPdoManager(): void
    {
        $app = new App();
        $factory = new PdoServiceFactory(
            $app->getEntityName(),
            $app->getStorageType(),
            $app->getDbFolderName()
        );
        $pdoManager = $factory->create();

        $this->assertTrue(method_exists($pdoManager, 'createTables'));
        $this->assertTrue(method_exists($pdoManager, 'getPdo'));
    }
}
